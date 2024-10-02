<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\DeviceRightService;
use App\Library\Interface\DeviceRightServiceInterface;
class DeviceRightServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DeviceRightServiceInterface::class, DeviceRightService::class);
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
