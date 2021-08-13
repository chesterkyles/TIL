<?php

namespace Tests\Unit\Services;

use App\Models\CareHistory;
use App\Models\StaffAccount;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Auth\UserRecord;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Tests\TestCase;

class FirebaseServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function createUserIsSuccessful(): void
    {
        $password = Str::random(8);
        $email = $this->faker->email;

        $mockUser = new UserRecord();
        $mockUser->email = $email;

        $this->mock(Auth::class, function ($mock) use ($mockUser) {
            $mock->shouldReceive('createUser')
                ->once()
                ->andReturn($mockUser);
        });

        $user = self::firebaseService()->createUser($email, $password);

        $this->assertNotEmpty($user);
        $this->assertSame($email, $user->email);
    }

    /**
     * @test
     */
    public function createUserFailedOnEmptyParam(): void
    {
        $this->expectException(\ArgumentCountError::class);
        self::firebaseService()->createUser();
    }

    /**
     * @test
     */
    public function sendPushNotificationToClientIsSuccessful(): void
    {
        $staff = StaffAccount::factory()->create([
            'firebase_token' => $this->faker->text(),
        ]);
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
        ]);

        $mockMessage = CloudMessage::new();
        $mockMessage = $mockMessage
            ->withTarget('token', $staff->firebase_token)
            ->withData([
                'type' => 'care_history',
                'id' => $careHistory->id,
            ]);

        $this->mock(FirebaseService::class, function ($mock) use ($mockMessage) {
            $mock->shouldReceive('sendPushNotificationToClient')
                ->once()
                ->andReturn($mockMessage);
        });

        $result = self::firebaseService()->sendPushNotificationToClient($careHistory->id, $staff->firebase_token);
        $output = $result->jsonSerialize();

        $this->assertNotEmpty($result);
        $this->assertEquals($careHistory->id, $output['data']->jsonSerialize()['id']);
        $this->assertEquals($staff->firebase_token, $output['token']);
    }

    /**
     * @test
     */
    public function sendPushNotificationToClientNotFoundMessageOnExpiredToken():void
    {
        $staff = StaffAccount::factory()->create([
            'firebase_token' => $this->faker->text(),
        ]);
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
        ]);

        $this->mock(Messaging::class, function ($mock) {
            $mock->shouldReceive('validateRegistrationTokens')
                ->once()
                ->andReturn(['invalid' => []])
                ->shouldReceive('send')
                ->once()
                ->andThrow(NotFound::class);
        });

        $this->expectException(FirebaseException::class);
        self::firebaseService()->sendPushNotificationToClient($careHistory->id, $staff->firebase_token);
    }

    /**
     * @test
     */
    public function sendPushNotificationToClientFailedOnInvalidToken():void
    {
        $staff = StaffAccount::factory()->create([
            'firebase_token' => $this->faker->text(),
        ]);
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
        ]);

        $this->expectException(FirebaseException::class);
        self::firebaseService()->sendPushNotificationToClient($careHistory->id, $staff->firebase_token);
    }

    private static function firebaseService()
    {
        return app()->make(FirebaseService::class);
    }
}
