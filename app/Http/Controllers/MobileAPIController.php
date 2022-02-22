<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileAccess;
use App\Library\Services\MessageService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DeviceAPIController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Library\Services\UserRightOnMobileTokenService;
use App\Models\DeviceList;
use App\Library\Services\DeviceRightService;
use App\Models\User;
use App\Http\Controllers\DeviceController;
use App\Library\Services\SubscriptionManagementService;

class MobileAPIController extends Controller
{
    public function fetch_message($access_token=null, $phone_token=null) {
        
        $result=[];
        if ($access_token != null && $phone_token != null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token','=', $phone_token)->get();
            
            if (count($data)>0) {
                $userId=$data[0]->user_id;
                if (SubscriptionManagementService::subscribed($userId)) {
                    $result=["result"=>"success", "data"=>MessageService::AllMessages($userId)];
                    self::last_access_update($access_token, $phone_token);
                } else {
                    $result=["result"=>"not-subscribed", "data"=>[]];
                }
                
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }
    public function delete_message($access_token=null, $phone_token=null, $msg_id=null){
        
        $result=[];
        if ($access_token != null && $msg_id != null &&  $phone_token!=null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token', '=', $phone_token)->get();
            if (count($data)>0) {
                $userId=$data[0]->user_id;
                $result=["result"=>"fail"];

               
                $data_to_del=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
                ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id')
                /*->where("user_id", "=", $userId)*/->where("msg_id", "=", $msg_id)/*->where("status", "=", "active")*/->get();
                
                if (count($data_to_del)==1 ) {
                    $device_id=$data_to_del[0]->device_id;
                    if ((DeviceRightService::SharedDeviceMiddleRight($userId, $device_id) || DeviceRightService::AbsoluteRightOnDevice($device_id, $userId))) {//Right Ensure
                        
                        if (SubscriptionManagementService::subscribed($userId)) {
                            $resN=DB::table('message')->where('msg_id', '=', $data_to_del[0]->msg_id)->delete();
                            if($resN==1){
                                $result= ["result"=> "success"];
                                self::last_access_update($access_token, $phone_token);
                            }
                        } else {
                            $result=["result"=>"not-subscribed"];
                        }
                        
                    }
                }

                //$result=["result"=>"success", "data"=>MessageService::AllMessages($userId)];
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }

    public function pin_message($access_token=null, $phone_token=null, $msg_id=null, $true_false=null){
        
        $result=[];
        if ($access_token != null && $msg_id != null && $true_false!=null && $phone_token != null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token', '=', $phone_token)->get();
            if (count($data)>0) {
                $userId=$data[0]->user_id;
                $result=["result"=>"fail"];

                $data_to_pin=DeviceList::join('message', 'message.device_case_id', '=', 'device_list.case_id')
                ->select('message.msg_id','message.message','message.datetime', 'device_list.device_id')
                /*->where("user_id", "=", $userId)*/->where("msg_id", "=", $msg_id)/*->where("status", "=", "active")*/->get();
    
                
                if (count($data_to_pin)==1 ) {
                    $device_id=$data_to_pin[0]->device_id;
                    if ((DeviceRightService::SharedDeviceMiddleRight($userId, $device_id) || DeviceRightService::AbsoluteRightOnDevice($device_id, $userId))) {//Right Ensure
                        
                        if (SubscriptionManagementService::subscribed($userId)) {
                            $resN=DB::table('message')->where('msg_id', '=', $data_to_pin[0]->msg_id)
                            ->update(['pin'=>$true_false]);               
                            if($resN==1){
                                $result= ["result"=> "success"];
                                self::last_access_update($access_token, $phone_token);
                            }
                        } else {
                            $result=["result"=>"not-subscribed"];
                        }
                        
                    }
                }
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }
    public function AccessTokenValidCheck($access_token=null, $phone_token=null){
        $result=[];
        if ($access_token != null && $phone_token!=null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token','=',$phone_token)->get();
            if (count($data)>0) {
                $result=["result"=>"valid"];
                self::last_access_update($access_token, $phone_token);
                
            }else{
                $result=["result"=>"non-valid", "subscribed"=>null];
            }
        }else{
            $result=["result"=>"non-valid-request", "subscribed"=>null];
        }
        return response()->json($result);
    }
    public function mobileConnect($access_token=null,$randStrFromPhone=null, Request $request){
        //echo $request->input('mobileInfo');
        if ($access_token != null && $randStrFromPhone != null) {
            $current_=MobileAccess::where('access_token', '=', $access_token)->get();
            if (count($current_)>0) {

                $current_phone_token=$current_[0]->phone_token;
                if ($current_phone_token != "") {
                    $result=["result"=>"access-key-used"];
                }else{
                    $res=MobileAccess::where('access_token', '=', $access_token)
                    ->update(['phone_token'=>$randStrFromPhone, 'mobile_info'=>$request->input('mobileInfo') or ""]);
                    if ($res==1) {
                        $result=["result"=>"connected"];
                        self::last_access_update($access_token, $randStrFromPhone);
                    }else{
                        $result=["result"=>"not-connected"];
                    }
                }

            } else {
                $result=["result"=>"non-valid-access-key"];
            }       

        }else{
            $result=["result"=>"non-valid-request"];
        }
        return response()->json($result);
    }


    public function basic_data($access_token=null, $phone_token=null){
        
        $result=[];
        if ($access_token != null && $phone_token != null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token', '=', $phone_token)->get();
            if (count($data)>0) {
                $userId=$data[0]->user_id;

                
                
                $userData=User::where('id','=', $userId)->get()[0];
                $result=["result"=>"success",
                "data"=>[
                    'name'=> $userData->name,
                    'email'=> $userData-> email,
                    'accessKeyNickname' => $data[0]->nickname,
                    'subscribed'=> SubscriptionManagementService::subscribed($userId)
                ]];
                self::last_access_update($access_token, $phone_token);
                
                
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }

    public function last_access_update($access_token, $phone_token){
        $res=MobileAccess::where('access_token', '=', $access_token)
        ->where('phone_token', '=', $phone_token)
        ->update(['last_access'=> gmdate("Y-m-d H:i:s P")]);
    }
    public function mobileDisconnect($access_token=null){
        $res=MobileAccess::where('access_token', '=', $access_token)
        ->update(['deleted_from_phone'=> "yes"]);
    }


    public function fetch_device_list($access_token=null, $phone_token=null, $device_id=null) {
        
        $result=[];
        if ($access_token != null && $phone_token != null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token','=', $phone_token)->get();
            
            if (count($data)>0) {
                $userId=$data[0]->user_id; 
                    if (SubscriptionManagementService::subscribed($userId)) {
                       
                        if ($device_id==null) {
                            $data1=DeviceList::select('case_id','device_id', 'info', 'datetime', 'status', 'nickname')->where("user_id", "=",$userId )->get();
                            $result=["result"=>"success", "data"=>  $data1];
                        } else {
                            //
                            if(DeviceRightService::AbsoluteRightOnDevice($device_id, $userId) || DeviceRightService::SharedDeviceAdvancedRight($userId, $device_id)){//Ensure the Right
                                $data1=DeviceList::where('device_id', $device_id)->get();
                                $result=["result"=>"success", "data"=>  $data1];
                            }else{            
                                $result=["result"=>"no-right", "data"=>  []];
                            }
                        }
                        self::last_access_update($access_token, $phone_token);
                    }else{
                        $result=["result"=>"not-subscribed", "data"=>[]];
                    }
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }


    /*public function device_button_test($access_token=null, $phone_token=null, $device_id=null, $button_id=null){
        $result=[];
        if ($access_token != null && $phone_token != null) {
            $data=MobileAccess::where('access_token', '=', $access_token)->where('phone_token','=', $phone_token)->get();
            
            if (count($data)>0) {
                $userId=$data[0]->user_id; 

                if ($device_id==null && ) {
                    $data1=DeviceList::select('case_id','device_id', 'info', 'datetime', 'status', 'nickname')->where("user_id", "=",$userId )->get();
                    $result=["result"=>"success", "data"=>  $data1];
                } else {
                    //
                    if(DeviceRightService::AbsoluteRightOnDevice($device_id, $userId) || DeviceRightService::SharedDeviceAdvancedRight($userId, $device_id)){//Ensure the Right
                        $data1=DeviceList::where('device_id', $device_id)->get();
                        $result=["result"=>"success", "data"=>  $data1];
                    }else{            
                        $result=["result"=>"no-right", "data"=>  []];
                    }
                }
                self::last_access_update($access_token, $phone_token);
            }else{
                $result=["result"=>"false-credentials", "data"=>[]];
            }
        }else{
            $result=["result"=>"non-valid-request", "data"=>[]];
        }
        return response()->json($result);
    }*/
}




//    

/*$bearerToken=hash('sha256',$request->bearerToken());
        $result=[];
        if($device_id != "" && $bearerToken != "" && $request->has('button_id')){
            $button_id=$request->input("button_id");
            $data=DeviceList::where('bearer_token', $bearerToken)//Ensure the Right
            ->where('device_id', $device_id)
            ->get();
            $result=DeviceAPIController::push_msg_internal($data, $button_id);
            
        }else{
            $result=["result"=>"non-valid-request"];
            //return response()->json()->header('Access-Control-Allow-Origin', '*');
        }
        return response()->json($result);

*/
    

  
