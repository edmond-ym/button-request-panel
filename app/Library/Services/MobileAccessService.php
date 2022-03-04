<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
use Illuminate\Support\Str;
use App\Library\Services\UserRightOnMobileTokenService;
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
    public static function MobileAccessDestroy($userId, $case_id){
        if (UserRightOnMobileTokenService::right($case_id, $userId)) {
            $r=MobileAccess::where('case_id', '=', $case_id)
            ->where('user_id', '=', $userId) // ensure user right on that
            ->delete();
            if ($r) {
                return (Object)['result'=>'success', 'case_id'=>$case_id];
            } else {
                return (Object)['result'=>'fail', 'case_id'=>$case_id];
            }
        }
        return (Object)['result'=>'no-privilege', 'case_id'=>$case_id]; 
    }
    public static function MobileAccessAmend($userId, $case_id, $updateArray){
        if (UserRightOnMobileTokenService::right($case_id, $userId)) {
            $r=false;
            if (count($updateArray)==0) {
                return (Object)['result'=>'invalid-query', 'data'=>[]];
            }
            $r=MobileAccess::where('case_id', '=', $case_id)
                    ->where('user_id', '=',$userId) // ensure user right on that
                    ->update($updateArray);
            $data=MobileAccess::select('case_id', 'nickname')->where('user_id', '=', $userId)->where('case_id','=', $case_id)->get();

            if ($r) {
                return (Object)['result'=>'success', 'data'=>$data];
            } else {
                return (Object)['result'=>'fail', 'data'=>$data];
            }
        }
        return (Object)['result'=>'no-privilege', 'data'=>[]]; 
    }

  
}