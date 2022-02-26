<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceList;
use App\Models\DeviceOwnershipShare;
use Illuminate\Support\Facades\Auth;
use App\Library\Services\DeviceRightService;
use Illuminate\Support\Facades\Validator;
use App\Rules\DeviceShareValidEmail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Library\Services\MessageService;
use App\Library\Services\SubscriptionManagementService;


class MessageController extends Controller
{
    //
    public function msg_delete($login_session=null,$message_id=null) {
        $result=["result"=>"fail"];
        if(Auth::check() && SubscriptionManagementService::offlineStatusSubscribed(Auth::id())){
            $userId=Auth::id();
            $data_to_del=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
            ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id')
            /*->where("user_id", "=", $userId)*/->where("msg_id", "=", $message_id)/*->where("status", "=", "active")*/->get();
            
            if (count($data_to_del)==1 ) {
                $device_id=$data_to_del[0]->device_id;
                if ((DeviceRightService::SharedDeviceMiddleRight($userId, $device_id) || DeviceRightService::AbsoluteRightOnDevice($device_id))) {
                    $resN=DB::table('message')->where('msg_id', '=', $data_to_del[0]->msg_id)->delete();
                    if($resN==1){
                        $result= ["result"=> "success"];
                    }
                }
            }
        }
        return response()->json($result)->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Headers', 'Authorization'); 

    }
    public function msg_pin($login_session=null,$message_id=null, $true_false=null) {
        $result=["result"=>"fail"];
        if(Auth::check() && SubscriptionManagementService::offlineStatusSubscribed(Auth::id())){
            $userId=Auth::id();
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
                    }
                }
            }
        }
        return response()->json($result)->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Headers', 'Authorization'); 

    }
   
    public function msg_enquiry($login_session=null) {
        if(Auth::check() && SubscriptionManagementService::offlineStatusSubscribed(Auth::id())){
            return  response()->json(MessageService::AllMessages(Auth::id()))->header('Access-Control-Allow-Origin', '*'); 
        }else{
            return response()->json(["result"=>"fail"]); 
        }
    }
    public function msg_dashboard_ui (Request $request) {
        if(Auth::check()){
            return view('dashboard.message', ['login_session'=> $request->session()->getId()]);
        }else{
            return "xxx";
        }
    }

}
