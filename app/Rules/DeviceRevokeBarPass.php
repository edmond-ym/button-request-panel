<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceList;
use Illuminate\Http\Request;

class DeviceRevokeBarPass implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type_field, Request $request)
    {
        $this->type_field=$type_field;
        $this->error_msg="";
        $this->request=$request;
        
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
    
        for ($i=0; $i < count($value); $i++) { 
            $current_status=DeviceList::where('user_id', Auth::id())//Ensure the Right
                       ->where('case_id', $value[$i])->get()[0]->status;
            if($this->request->input($this->type_field)=="revoke"){
                if($current_status!="suspend"){
                    $this->error_msg="Only Suspended Item can be revoked";
                    return false;
                }
            }
        }
        return true;
        //
        /**/
                    
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_msg;
    }
}
