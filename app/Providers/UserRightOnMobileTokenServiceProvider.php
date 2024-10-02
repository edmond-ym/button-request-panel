<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\UserRightOnMobileTokenService;
use App\Library\Interface\UserRightOnMobileTokenServiceInterface;

class UserRightOnMobileTokenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRightOnMobileTokenServiceInterface::class, UserRightOnMobileTokenService::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
