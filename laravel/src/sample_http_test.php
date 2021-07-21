<?php

namespace Tests\Feature\Api;

use App\Models\CareHistory;
use App\Models\CareRepitent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CareHistoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use StaffAccountGenerator;

    private const BASE_PATH = '/api/v1/care_histories';

    /**
     * @test
     */
    public function itShouldReturnListWithRepitentIdAndStatusIfSuccessful(): void
    {
        $staff = $this->createStaffUser();
        $careRepitent = CareRepitent::factory()->create();
        CareHistory::factory()->count(5)->approved()->create([
            'staff_account_id' => $staff->id,
            'care_repitent_id' => $careRepitent->id,
        ]);

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth' => $staff->firebase_uid])
            ->json('GET', self::BASE_PATH . '?care_repitent_id=' . $careRepitent->id . '&status=1')
            ->assertStatus(200)
            ->assertJson([
                'result' => [
                    [
                        'staff_account_id' => $staff->id,
                        'care_repitent_id' => $careRepitent->id,
                        'status' => 1,
                    ]
                ],
            ]);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldReturnNotFoundIfListIsEmpty(): void
    {
        $staff = $this->createStaffUser();
        $careRepitent = CareRepitent::factory()->create();
        CareHistory::factory()->count(5)->approved()->create([
            'staff_account_id' => $staff->id,
            'care_repitent_id' => $careRepitent->id,
        ]);
        $nonExistingCareRepitentId = 9999;

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth' => $staff->firebase_uid])
            ->json('GET', self::BASE_PATH . '?care_repitent_id=' . $nonExistingCareRepitentId . '&status=1')
            ->assertStatus(404)
            ->assertJson(['message' => 'Not found!']);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldGetCareHistoryDetailIfSuccessful(): void
    {
        $staff = $this->createStaffUser();
        $careRepitent = CareRepitent::factory()->create();
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
            'care_repitent_id' => $careRepitent->id,
        ]);

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth' => $staff->firebase_uid])
            ->json('GET', self::BASE_PATH . '/' . $careHistory->id)
            ->assertStatus(200)
            ->assertJson([
                'result' => [
                    'id' => $careHistory->id,
                    'date' => $careHistory->date,
                    'staff_account_id' => $staff->id,
                    'care_repitent_id' => $careRepitent->id,
                    'status' => $careHistory->status,
                    'memo_s' => $careHistory->memo_s,
                    'memo_o' => $careHistory->memo_o,
                    'memo_a' => $careHistory->memo_a,
                    'memo_p' => $careHistory->memo_p,
                ],
            ]);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldReturnNotFoundIfNoCareHistoryDetail(): void
    {
        $staff = $this->createStaffUser();
        $nonExistingCareHistoryId = 99999;

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth' => $staff->firebase_uid])
            ->json('GET', self::BASE_PATH . '/' . $nonExistingCareHistoryId)
            ->assertStatus(404)
            ->assertJson(['message' => 'Not found!']);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldReturnNotAuthorizedIfStaffAccountIdIsIncorrect(): void
    {
        $staff = $this->createStaffUser();
        $nonExistingStaffAccountId = 99999;
        $careRepitent = CareRepitent::factory()->create();
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $nonExistingStaffAccountId,
            'care_repitent_id' => $careRepitent->id,
        ]);

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth' => $staff->firebase_uid])
            ->json('GET', self::BASE_PATH . '/' . $careHistory->id)
            ->assertStatus(403)
            ->assertJson(['message' => 'Not authorized!']);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldReturnOkIfStoreIsSuccessful(): void
    {
        $auth = app('firebase.auth');
        $password = $this->faker->regexify('[A-Za-z0-9]{10}');
        $staff = $this->createStaffUser($password);
        $signedInUser = $auth->signInWithEmailAndPassword($staff->email, $password);

        $careRepitent = CareRepitent::factory()->create();
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
            'care_repitent_id' => $careRepitent->id,
        ]);

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth-api-token' => $signedInUser->idToken()])
            ->json('POST', self::BASE_PATH . '/' . $careHistory->id, [
                'date' => $this->faker->date(),
                'care_repitent_id' => $careRepitent->id,
                'staff_account_id' => $staff->id,
                'type' => $this->faker->randomElement(['s', 'o', 'a', 'p']),
                'memo' => $this->faker->text(),
                'filename' => 'sample.mp4', //FIXME: This should be a successfully uploaded file
            ])
            ->assertStatus(200)
            ->assertJson(['result' => 'OK']);

        $this->removeFirebaseUser($staff->firebase_uid);
    }

    /**
     * @test
     */
    public function itShouldReturnNotAuthroizedIfAccessTokenIsIncorrect(): void
    {
        $accessToken = $this->faker->text();
        $staff = $this->createStaffUser();
        $careRepitent = CareRepitent::factory()->create();
        $careHistory = CareHistory::factory()->create([
            'staff_account_id' => $staff->id,
            'care_repitent_id' => $careRepitent->id,
        ]);

        $this
            ->actingAs($staff, 'api-staff')
            ->withHeaders(['x-auth-api-token' => $accessToken])
            ->json('POST', self::BASE_PATH . '/' . $careHistory->id)
            ->assertStatus(403)
            ->assertJson(['message' => 'The given token could not be parsed: The JWT string must have two dots']);

        $this->removeFirebaseUser($staff->firebase_uid);
    }
}
