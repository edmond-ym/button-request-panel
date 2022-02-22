<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Models\DeviceList;
use App\Models\DeviceOwnershipShare;
use App\Models\Subscriptions;

class User extends Authenticatable
{
    
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;



    //use Model;
    

    protected static function booted(){
        //echo Subscriptions::all();
        static::updated(\Illuminate\Events\queueable(function ($customer) {
            //echo $customer;
            $customer->syncStripeCustomerDetails();
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
                
            }
        }));
        
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function deviceList()
    {
        return $this->hasMany(DeviceList::class, 'user_id', 'id');
    }
    public function deviceSharedToMe()
    {
        return $this->hasMany(DeviceOwnershipShare::class, 'share_to_user_id', 'id');
    }
    public function messageOfMyDevice(){
        return $this->hasManyThrough(
            MessageEnquiry::class, //Final
            DeviceList::class, //Intermediate
            'user_id',  //foreign key on the intermediate model
            'device_case_id', //foreign key on the final model
            'id', // the local key
            'case_id' // local key of the intermediate model
        );
    }

    public function messageSharedToMe(){
        /*return $this->hasManyThrough(
            MessageEnquiry::class, //Final
            DeviceOwnershipShare::class, //Intermediate
            'share_to_user_id',  //foreign key on the intermediate model
            'device_case_id', //foreign key on the final model
            'id', // the local key
            'device_id' // local key of the intermediate model
        );*/
    }
}

