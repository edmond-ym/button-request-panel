<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Library\Services\CommonService;
use App\Library\Interface\CommonServiceInterface;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommonServiceInterface::class, CommonService::class);

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
