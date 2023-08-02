<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Services\Auth\DatabaseLoginService;
use App\Services\Auth\LoginServiceInterface;
use App\Services\Auth\RedisLoginService;
use App\Services\Auth\SessionLoginService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->app->bind(LoginServiceInterface::class, SessionLoginService::class);
//        $this->app->bind(LoginServiceInterface::class, RedisLoginService::class);
//        $this->app->bind(LoginServiceInterface::class, DatabaseLoginService::class);
    }
}
