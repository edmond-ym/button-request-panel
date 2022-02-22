<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DeviceOwnershipShare;

class DeviceShareValidEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($device_id)
    {
        $this->device_id=$device_id;
        $this->error_message="";
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $myEmail=User::where('id', '=', Auth::id())->get()[0]->email;
        if ($myEmail==$value) {
            $this->error_message="Email is the same as yours";
            return false;//same as myemail
        }else{
            if(count(User::where('email', '=', $value)->get())>0){
                $userIdToReceiveShare=User::where('email', '=', $value)->get()[0]->id;
                if (count(DeviceOwnershipShare::where('share_to_user_id', '=', $userIdToReceiveShare)->where('device_id','=', $this->device_id)->get())>0) {
                    $this->error_message="Added already";
                    return false;//exist already
                }else{
                    return true;
                }

            }else{
                $this->error_message="No this email";
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
