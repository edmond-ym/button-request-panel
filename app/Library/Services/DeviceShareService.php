<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Library\Services\DeviceRightService;
use App\Rules\DeviceShareValidEmail;

class DeviceShareService
{
    function __construct(){

    }

    public static function GiveUpShareeRight($userId, $case_id){
        $data=DeviceOwnershipShare::where('share_to_user_id', '=', $userId) // ensure the right
        ->where('case_id', '=', $case_id)->get();
        if (count($data)>0) {
            $r=DeviceOwnershipShare::where('share_to_user_id', '=', $userId) // ensure the right
            ->where('case_id', '=', $case_id)->delete();
            if ($r) {
                return (Object)["result"=>"success", "case_id"=>$case_id];
            }
            return (Object)["result"=>"fail", "case_id"=>$case_id];
        }
        return (Object)["result"=>"no-privilege", "case_id"=>$case_id];
    
    }

    public static function ShareNewDevice($userId, $shareToEmail, $deviceId){
        $validator=Validator::make(['email'=>$shareToEmail], [
            'email' => ['email',new DeviceShareValidEmail($deviceId, true)], //non editable
        ],

        [
            'email.email' => 'not-valid-email',
        ]
        );        
        
        if (!$validator->fails()) {
            
            $targetId=User::where('email', '=', $shareToEmail)->get()[0]->id;
            if (DeviceRightService::AbsoluteRightOnDevice($deviceId)) {
                $r=DeviceOwnershipShare::insert([
                    'case_id'=>Str::random(40),
                    'device_id'=>$deviceId,
                    'share_to_user_id'=>$targetId,
                    'right'=>'basic',
                    'created_time'=>gmdate("Y-m-d H:i:s P")
                ]);
                if ($r) {
                    if (env('MAIL_ENABLED')) {
                         Mail::to($shareToEmail)
                         ->send(new \App\Mail\DeviceShared("sharee", $userId, $targetId, $deviceId));
         
                         Mail::to(User::find($userId)->email)
                         ->send(new \App\Mail\DeviceShared("sharer", $userId, $targetId, $deviceId));
                    }
                    return (Object)["result"=>"success"];
                }
                return (Object)["result"=>"fail"];
            } 
            return (Object)["result"=>"no-privilege"];
        }
        return (Object)["result"=> "not-valid-email"];
        //return (Object)["result"=> $validator->errors()->first('email')];

    }

    public static function changeShareeRight($userId, $case_id, $new_right){
   
        $data=DeviceOwnershipShare::where("case_id",'=', $case_id)->get();
        $right_allowed=['basic', 'middle', 'advanced'];
        $new_right=strtolower($new_right);
        if (in_array($new_right, $right_allowed)) {
            if (count($data)>0) {
                $device_id=$data[0]->device_id;
                if(DeviceRightService::AbsoluteRightOnDevice($device_id, $userId)){//Protection
                    
                    $r=DeviceOwnershipShare::where('device_id', '=', $device_id)->where('case_id', '=', $case_id)->update(['right'=>$new_right]);
                    if ($r) {
                        
                        return (Object)["result"=>"success"];
                    }
                    return (Object)["result"=>"fail"];
                    
                }
                return (Object)["result"=>"no-privilege"];
        
            }
            return (Object)["result"=>"case-id-not-exist"];
        }
        return (Object)["result"=>"not-valid-right"];
    }

    public static function revokeShareeRight($userId, $case_id ){
        $data=DeviceOwnershipShare::where("case_id",'=', $case_id)->get();

        if (count($data)>0) {
            $device_id=$data[0]->device_id;
            if(DeviceRightService::AbsoluteRightOnDevice($device_id, $userId)){
                $r=DeviceOwnershipShare::where('device_id', '=', $device_id)->where('case_id', '=', $case_id)->delete();
                if ($r) {
                    return (Object)["result"=>"success"];
                }
                return (Object)["result"=>"fail"];
            }
            return (Object)["result"=>"no-privilege"];
        }
        return (Object)["result"=>"case-id-not-exist"];
        
    }


    




}