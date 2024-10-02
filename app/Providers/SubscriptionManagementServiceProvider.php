<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\SubscriptionManagementService;
use App\Library\Interface\SubscriptionManagementServiceInterface;

class SubscriptionManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriptionManagementServiceInterface::class, SubscriptionManagementService::class);

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
