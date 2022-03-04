<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;

class UserRightOnMobileTokenService
{
    
    function __construct() {
       
    }
    public static function right ($mobile_token_case_id, $userId=null){
        if ($userId==null) {
            $userId=Auth::id();
        }
        $data = MobileAccess::where('case_id', '=', $mobile_token_case_id)
        ->where('user_id', '=', $userId) // ensure user right on that
        ->get();

        if (count($data)>0) {
            return true;
        }
        return false;
    }
    
    

    


    
}