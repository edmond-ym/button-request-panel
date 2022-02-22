<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use App\Policies\DeviceListPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\DeviceList;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        //DeviceList::class=>DeviceListPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
       /* Gate::define('device-absolute-right', function(User $user, DeviceList $deviceList, $device_id){
            $data=DeviceList::where('user_id', '=', $user->id)->where('device_id', '=', $device_id);
            return count($data)>0;
        });*/
    }
}
