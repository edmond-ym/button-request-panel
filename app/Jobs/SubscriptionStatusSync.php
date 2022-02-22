<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscriptions;
use App\Models\User;
use App\Library\Services\SubscriptionManagementService as SMS;

class SubscriptionStatusSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $all=User::all();
    
        for ($i=0; $i < count($all); $i++) { 
            $userId=$all[$i]->id;
            User::where('id', '=', $userId)->update(['subscription_status_updated'=>'false']);
        }
    }
}
//