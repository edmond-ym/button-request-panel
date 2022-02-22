<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;

class DeviceRightService
{
    function __construct(User $user, DeviceList $deviceList) {
        $this->user=$user;
        $this->deviceList=$deviceList;
        
    }
    public static function AbsoluteRightOnDevice($device_id, $userId=null){
        
        if ($userId==null) {
            $userId=Auth::id();
        }
        $data=DeviceList::where('user_id', $userId)//Ensure the Right
                ->where('device_id', $device_id)
                ->get();
       
        if (count($data)>0) {
            return true;
        }else{
            return false;
        }
    }
    public static function SharedDeviceAdvancedRight($user_id, $device_id){
        $data=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'advanced')->get();
        if(count($data)>0){
            return true;
        }else{
            return false;
        }
    }
    public static function ShareDeviceBasicRight($user_id, $device_id){
        $dataBasic=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'basic')->get();
        $dataMiddle=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'middle')->get();
        $dataAdvanced=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'advanced')->get();
        if(count($dataBasic)>0 || count($dataMiddle)>0 || count($dataAdvanced)>0){
            return true;
        }else{
            return false;
        }
        
    }
    public static function SharedDeviceMiddleRight($user_id, $device_id){
        $dataMiddle=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'middle')->get();
        $dataAdvanced=DeviceOwnershipShare::where('share_to_user_id','=',$user_id)->where('device_id','=',$device_id)
        ->where('right', '=', 'advanced')->get();
        if(count($dataMiddle)>0 || count($dataAdvanced)>0){
            return true;
        }else{
            return false;
        }
    }

}

//->orWhere('right', '=', 'basic')
//->orWhere('right', '=', 'middle')->orWhere('right', '=', 'basic')