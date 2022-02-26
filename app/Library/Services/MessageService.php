<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use Illuminate\Support\Facades\DB;

class MessageService
{
    function __construct() {
        
        
    }
    public static function MessageOfMyDevice($userId){
            
        $data1=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
        ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id', 'device_list.nickname', 'message.pin')
        ->where("user_id", "=", $userId)->orderBy('message.datetime', 'desc')->get();

        return $data1;
    }

    public static function MessageSharedToMe($userId){
        $data2=DeviceOwnershipShare::join('device_list', 'device_list.device_id', '=', 'device_ownership_share.device_id' )
        ->join('message', 'message.device_case_id', '=', 'device_list.case_id')
        ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id', 'device_list.nickname', 'message.pin', 'device_ownership_share.right')
        ->where("device_ownership_share.share_to_user_id", "=", $userId)->orderBy('message.datetime', 'desc')->get();
        return $data2;
    }
    public static function AllMessages($userId){
        $data1=self::MessageOfMyDevice($userId);
            //
        foreach($data1 as $item1) {
            $item1['shared_to_me']='false';
            $item2['right']='absolute';
        }
        //
        
        $data2=self::MessageSharedToMe($userId);
        //
        //Basic Right
        foreach($data2 as $item2) {
            $item2['shared_to_me']='true';
        }
        $d1_collection=collect($data1);
        $d2_collection=collect($data2);
        $mergedCollection=$d1_collection->merge($d2_collection);
        return $mergedCollection;
    }
    public static function MessagesCount($userId){
        $output_array=(object)[
            'myDevice' => count(self::MessageOfMyDevice($userId)),
            'sharedToMe' => count(self::MessageSharedToMe($userId)),
        ];
        return $output_array;
    }
    public static function AllMessagesNicknameList($userId){
        $new_array=array();
        $array=self::AllMessages($userId);
        
        $nickname_arr=collect($array)->pluck(['nickname'])->all();
        $device_id_arr=collect($array)->pluck(['device_id'])->all();
        
        for ($i=0; $i < count($array); $i++) { 
            $to_be_pushed=['nickname'=>$nickname_arr[$i], 'device_id'=>$device_id_arr[$i]];
            if (!in_array($to_be_pushed, $new_array)) {
                array_push($new_array, $to_be_pushed);
            }
        }
        return $new_array;
    }


    public static function pinMessage($userId, $message_id, $true_false){
        //$userId=Auth::id();
        $result=["result"=> "fail"];
        $data_to_pin=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
        ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id')
        /*->where("user_id", "=", $userId)*/->where("msg_id", "=", $message_id)/*->where("status", "=", "active")*/->get();
        
        if (count($data_to_pin)==1 ) {
            $device_id=$data_to_pin[0]->device_id;
            
            if ((DeviceRightService::SharedDeviceMiddleRight($userId, $device_id) || DeviceRightService::AbsoluteRightOnDevice($device_id))) {
                $resN=DB::table('message')->where('msg_id', '=', $data_to_pin[0]->msg_id)
                ->update(['pin'=>$true_false]);               
                if($resN==1){
                    $result= ["result"=> "success"];
                }else{
                    $result=["result"=> "fail"];
                }
            }else{
                $result=["result"=> "no-privilege"];
            }
        }else{
            $result=["result"=> "not-exist"];
        }
        return $result;
    }

    public static function deleteMessage($userId, $message_id){
        $result=['result'=>'fail'];
        $data_to_del=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
        ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id')
        /*->where("user_id", "=", $userId)*/->where("msg_id", "=", $message_id)/*->where("status", "=", "active")*/->get();
        
        if (count($data_to_del)==1 ) {
            $device_id=$data_to_del[0]->device_id;
            if ((DeviceRightService::SharedDeviceMiddleRight($userId, $device_id) || DeviceRightService::AbsoluteRightOnDevice($device_id))) {
                $resN=DB::table('message')->where('msg_id', '=', $data_to_del[0]->msg_id)->delete();
                if($resN==1){
                    $result= ["result"=> "success"];
                }else{
                    $result= ["result"=> "fail"];
                }
            }else{
                $result= ["result"=> "no-privilege"];
            }
        }else{
            $result= ["result"=> "not-exist"];
        }
        return $result;
    }
   

}