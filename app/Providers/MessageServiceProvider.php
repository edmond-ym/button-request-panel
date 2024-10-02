<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\MessageService;
use App\Library\Interface\MessageServiceInterface;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MessageServiceInterface::class, MessageService::class);

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
