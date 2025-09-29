<?php

namespace App\Providers;

use App\Models\PrayerRequest;
use App\Policies\PrayerRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PrayerRequest::class => PrayerRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a 'manage-prayer-requests' gate
        Gate::define('manage-prayer-requests', function ($user) {
            return $user->role === 'Admin' || $user->role === 'Leader';
        });
    }
}
