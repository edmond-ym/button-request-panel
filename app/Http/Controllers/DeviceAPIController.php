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


class DeviceAPIController extends Controller
{
    public function push_msg($device_id=null, Request $request) {
        
        
        $bearerToken=hash('sha256',$request->bearerToken());
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
        return response()->json($result);
    }


    public static function push_msg_internal($deviceListIndiData, $button_id){
        $data=$deviceListIndiData;
        $result=[];
        if (count($data)!=1 || $data[0]->status=="suspend") {
            $result=["result"=>"no-privilege"];
            //return response()->json()->header('Access-Control-Allow-Origin', '*');
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
                $result=["result"=>"non-exist"];
                //return response()->json()->header('Access-Control-Allow-Origin', '*'); 
            }else{
                $repeated_message=$data[0]->repeated_message;
                if ($repeated_message=='no') {
                    
                    $length=count(DeviceList::find($data[0]->case_id)->messageEnquiry()->where('message','=',$msg)->get());
                    
                    if ($length>0) {
                        $result=["result"=>"no-repeated-message"];
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
                                $result=["result"=>"success"];
                            }else{
                                $result=["result"=>"fail"];
                            }
                        } else {
                            $result=["result"=>"not-subscribed"];
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
                            $result=["result"=>"success"];
                        }else{
                            $result=["result"=>"fail"];
                        }
                    }else{
                        $result=["result"=>"not-subscribed"];
                    }
                    
                }  
            }

        }
        return $result;


    }
  
}
