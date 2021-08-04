<?php

namespace App\Providers;

use App\Models\StaffAccount;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (HttpRequest $request) {
            return $this->checkFirebaseUser($request->bearerToken());
        });

        Gate::define('vendor', function($user) {
            if ($user->isVendorUser()) {
                return $user->vendor->id == Request::route()->parameter('vendor')->id;
            }

            return false;
        });

        Gate::define('agency', function($user) {
            if ($user->isVendorUser()) {
                return $user->vendor->hasAgencyAuthority(Request::route()->parameter('agency')->id);
            } elseif ($user->isAgencyUser()) {
                return $user->agency->id == Request::route()->parameter('agency')->id;
            }

            return false;
        });

        Gate::define('facility', function($user) {
            if ($user->isVendorUser()) {
                return $user->vendor->hasFacilityAuthority(Request::route()->parameter('facility')->id);
            } elseif ($user->isAgencyUser()) {
                return $user->agency->hasFacilityAuthority(Request::route()->parameter('facility')->id);
            } else if ($user->isFacilityUser()) {
                return $user->facility->id == Request::route()->parameter('facility')->id;
            }

            return false;
        });

        Passport::routes();
    }

    private function checkFirebaseUser(?string $token)
    {
        $auth = app('firebase.auth');

        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
        } catch (InvalidToken $e){
            \Log::error($e->getMessage());
            return null;
        } catch (\InvalidArgumentException $e) {
            \Log::error($e->getMessage());
            return null;
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        return StaffAccount::where('firebase_uid', $uid)->first();
    }
}
