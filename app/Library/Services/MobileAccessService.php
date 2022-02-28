<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
use Illuminate\Support\Str;

class MobileAccessService
{
    function __construct() {
       
    }
    public static function NewMobileAccess($userId,$nickname){
        $case_id=Str::random(15);
        $r=MobileAccess::insert([
            'case_id' => $case_id,
            'access_token' => Str::random(100),
            'user_id' =>$userId,
            //'eligible_device' => '',
            'nickname' => $nickname
        ]);
        if ($r) {
            return (Object)['result'=>'success', 'case_id'=>$case_id];
        } else {
            return (Object)['result'=>'fail'];

        }
        
    }
}