<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
class BasicUserRelatedInfoService
{
    
    function __construct() {
       
    }
    public static function deviceList(){
        
        $output_array=(object)[
            'number' => count(DeviceList::where('user_id', '=', Auth::id())->get()),
            'active_number' => count(DeviceList::where('user_id', '=', Auth::id())->where('status','=','active')->get()),
            'suspend_number' => count(DeviceList::where('user_id', '=', Auth::id())->where('status','=','suspend')->get()),
        ];
        return $output_array;
    }
    public static function deviceSharedToMe(){
        $data=DeviceOwnershipShare::where("share_to_user_id", "=", Auth::id())//->where('device_list.status', '='."'active'")
        ->join('device_list', 'device_list.device_id', '=', 'device_ownership_share.device_id')
        ->join('users', 'device_list.user_id', '=', 'users.id')
        ->get();
        $output_array=(object)[
            'number' => count($data),
        ];
        return $output_array;
    }
    public static function messages(){
        $output_array=(object)[
            'myDevice' => MessageService::MessagesCount(Auth::id())->myDevice,
            'sharedToMe' => MessageService::MessagesCount(Auth::id())->sharedToMe,
        ];
        return $output_array;
    }
    public static function mobileKey(){
        $MobileAccessWhereUserId=MobileAccess::where('user_id', '=', Auth::id());
        $output_array=(object)[
            'connectedNumber'=>count(MobileAccess::where('user_id', '=', Auth::id())->where('deleted_from_phone', '=', null)
                               ->whereNotNull('phone_token')
                               ->get()),
            'disconnectedNumber'=>count(MobileAccess::where('user_id', '=', Auth::id())->whereNotNull('phone_token')
                      ->where('deleted_from_phone', "=", "yes")->get()), //ok
            'notConnectedNumber'=>count(MobileAccess::where('user_id', '=', Auth::id())->whereNull('phone_token')
                                ->whereNull('deleted_from_phone')->get()), //ok
            'totalNumber'=>count(MobileAccess::where('user_id', '=', Auth::id())->get()) //ok
        ];
            
        return $output_array;
    }
    public static function forDashboard(){
        

        $output_array=[
            'myDevice' => self::deviceList(),
            'deviceSharedToMe' => self::deviceSharedToMe(),
            'messages' => self::messages(), 
            'mobileKey'=>self::mobileKey()
        ];
        return $output_array;

    }

    
}

