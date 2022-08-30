<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceList;
use App\Library\Services\DeviceRightService;
use App\Rules\DeviceRevokeBarPass;
use App\Models\MessageEnquiry;
use App\Models\User;
use App\Library\Services\SubscriptionManagementService;
use App\Library\Services\CommonService;


class DeviceAPIController extends Controller
{
    public function push_msg( Request $request, $device_id=null) {
       /* $bearerToken=hash('sha256',$request->bearerToken());
        $result=[];
        if($device_id != "" && $bearerToken != "" && $request->has('button_id')){
            $button_id=$request->input("button_id");
            $data=DeviceList::where('bearer_token', $bearerToken)//Ensure the Right
            ->where('device_id', $device_id)
            ->get();
            $result=self::push_msg_internal($data, $button_id);
        }else{
            $result=["result"=>"non-valid-request"];
            //return response()->json()->header('Access-Control-Allow-Origin', '*');
        }
        return response()->json($result);*/

        $result=[];
        if($device_id != "" && $request->bearerToken() != "" && $request->has('button_id')){
            $button_id=$request->input("button_id");
            $data=DeviceList::where('device_id', $device_id)
            ->get();
            if (count($data)==1) {
               
                $res=CommonService::DeviceAPIDecrypt($data[0]->bearer_token);
                if ($res->result=="success") {
                    if ($res->decryptedString==$request->bearerToken() ) {// Ensure Right
                        $result=self::push_msg_internal($data, $button_id);
                    }else{
                        $result=["result"=>"wrong-bearer-token", "data"=>[]];
                    }
                } else {
                    $result=["result"=>"decryption-failed", "data"=>[]];
                }
            }else{
                $result=["result"=>"device-id-not-exist", "data"=>[]];
            }

            
        }else{
            $result=["result"=>"not-valid-request", "data"=>[]];
            //return response()->json()->header('Access-Control-Allow-Origin', '*');
        }
        return response()->json($result);


    }


    public static function push_msg_internal($deviceListIndiData, $button_id){
        $data=$deviceListIndiData;
        $result=[];
        if (count($data)!=1  ) {
            $result=["result"=>"device-id-not-exist", "data"=>[]];
        }else if($data[0]->status=="suspend"){
            $result=["result"=>"device-suspended", "data"=>[]];
        }else{
            $info=$data[0]->info;
            $infoArr=json_decode($info);
            //
            $exist=false;
            $msg="";
            foreach ($infoArr as $item) {
                if($button_id==$item->buttonNo){
                    $exist=true;
                    $msg=$item->message;
                }
            }
            if(!$exist){
                $result=["result"=>"button-id-not-exist", "data"=>[]];
                //return response()->json()->header('Access-Control-Allow-Origin', '*'); 
            }else{
                $repeated_message=$data[0]->repeated_message;
                if ($repeated_message=='no') {
                    
                    $length=count(DeviceList::find($data[0]->case_id)->messageEnquiry()->where('message','=',$msg)->get());
                    
                    if ($length>0) {
                        $result=["result"=>"no-repeated-message", "data"=>[]];
                    } else {
                        if (SubscriptionManagementService::subscribed(DeviceList::find($data[0]->case_id)->user_id)) {
                            $data1=MessageEnquiry::insert([
                                'msg_id' => Str::random(40),
                                'device_case_id' => $data[0]->case_id,
                                'message' => $msg,
                                'datetime' =>  gmdate("Y-m-d H:i:s P"),
                                'pin' => 'false'
                            ]);
                            if($data1){
                                $result=["result"=>"success", "data"=>[
                                    [
                                        'msg_id' => Str::random(40),
                                        'message' => $msg,
                                        'datetime' =>  gmdate("Y-m-d H:i:s P"),
                                    ]
                                ]];
                            }else{
                                $result=["result"=>"fail", "data"=>[]];
                            }
                        } else {
                            $result=["result"=>"not-subscribed", "data"=>[]];
                        }
                        
                        
                    }
                    
                }else{
                    if (SubscriptionManagementService::subscribed(DeviceList::find($data[0]->case_id)->user_id)) {
                        $data1=MessageEnquiry::insert([
                            'msg_id' => Str::random(40),
                            'device_case_id' => $data[0]->case_id,
                            'message' => $msg,
                            'datetime' =>  gmdate("Y-m-d H:i:s P"),
                            'pin' => 'false'
                        ]);
                        if($data1){
                            $result=["result"=>"success", "data"=>[]];
                        }else{
                            $result=["result"=>"fail", "data"=>[]];
                        }
                    }else{
                        $result=["result"=>"not-subscribed", "data"=>[]];
                    }
                    
                }  
            }

        }
        return $result;


    }

    public function button_list(Request $request, $device_id){
        if ($request->bearerToken() != "") {
            $data=DeviceList::where('device_id', $device_id)->get();

            if (count($data)==1) {
                $res=CommonService::DeviceAPIDecrypt($data[0]->bearer_token);
                if ($res->result=="success") {
                    if ($res->decryptedString==$request->bearerToken() ) {// Ensure Right
                        
                        try{           
                            $button_list=$data[0]->info;
                            $result=[
                                "result"=>"success", 
                                "data"=>CommonService::ObjectInArrayMustHaveKey(json_decode($button_list, true), ["buttonNo", "message", "nickname"])         
                                ];
                        }catch (Exception $e){
                            $result=["result"=>"button-list-data-invalid", "data"=>[]];
                        }

                    }else{
                        $result=["result"=>"wrong-bearer-token", "data"=>[]];
                    }
                } else {
                    $result=["result"=>"decryption-failed", "data"=>[]];
                }
            }
            else{
                $result=["result"=>"device-id-not-exist", "data"=>[]];
            }
        }else{
            $result=["result"=>"bearer-token-missing", "data"=>[]];
        }

        return response()->json($result);


    }

  
}




    
               
   
