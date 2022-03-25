<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
use Illuminate\Support\Str;
use App\Library\Services\DeviceRightService;
use App\Library\Services\CommonService;


class DeviceListService
{
    function __construct(){

    }

    public static function newDeviceGenerate($user_id, $nickname){
        if ($nickname == "" or $nickname==null) {
            return (Object)['result'=>'missing-nickname', 'data'=>[]];
        }
        $case_id=Str::random(10);
        $device_id=Str::uuid();

        $bearerToken='dev_'.Str::random(40);
        
        $r=DeviceList::insert([
            'case_id' => $case_id,
            'device_id' => $device_id,
            'user_id' => $user_id,
            'bearer_token' => CommonService::DeviceAPIEncrypt($bearerToken),
            //'info' => $validated['info'],
            'info' => '[]',
            'datetime' =>  gmdate("Y-m-d H:i:s P"),
            'status' =>  'active', //active/suspend
            'nickname'=> $nickname,
            //'info_saved_for_later'=>$save_for_later
        ]);
        if ($r) {
            return (Object)['result'=>'success', 'data'=>[
                'case_id' => $case_id,
                'device_id' => $device_id,
                'user_id' => $user_id,
                'bearer_token' => $bearerToken,
                'info' => '[]',
                'datetime' =>  gmdate("Y-m-d H:i:s P"),
                'status' =>  'active', //active/suspend
                'nickname'=> $nickname,
            ]];
        } else {
            return (Object)['result'=>'fail', 'data'=>[]];
        }
    }

    public static function repeatMessageAllowUpdate($user_id,$device_id, $allowOrDisallow){
        if(DeviceRightService::AbsoluteRightOnDevice($device_id, $user_id) || DeviceRightService::SharedDeviceAdvancedRight($user_id, $device_id)){//Ensure the Right
            
            if ($allowOrDisallow=="allow") {
                $r=DeviceList::where('device_id', $device_id)
                ->where('user_id', '=', $user_id)
                ->update(['repeated_message'=>null]);
                if ($r) {
                    return (Object)['result'=>'success', 'data'=>
                        DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                        ->where("user_id", "=",$user_id)->where("device_id","=",$device_id)->get()
                    ];
                }else{
                    return (Object)['result'=>'fail', 'data'=>[]];
                }
            } elseif($allowOrDisallow=="disallow") {
                $r=DeviceList::where('device_id', $device_id)
                ->where('user_id', '=', $user_id)
                ->update(['repeated_message'=>"no"]);
                if ($r) {
                    return (Object)['result'=>'success', 'data'=>
                        DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                        ->where("user_id", "=",$user_id)->where("device_id","=",$device_id)->get()
                    ];
                }else{
                    return (Object)['result'=>'fail', 'data'=>[]];
                }
            }else{
                return (Object)['result'=>'invalid-command', 'data'=>[]];
            }
            
        }
        return (Object)['result'=>'no-privilege', 'data'=>[]];

    }


    public static function buttonMessageConfigure($user_id, $device_id, $action, $passData){
        if(DeviceRightService::AbsoluteRightOnDevice($device_id, $user_id) || DeviceRightService::SharedDeviceAdvancedRight($user_id, $device_id)){//Ensure the Right
            $buttonIdMsgJson=DeviceList::where('device_id', $device_id)->get()[0]->info;
            $buttonIdMsgArray=json_decode($buttonIdMsgJson, true);
            //print_r($passData);
            
            if ($action == "delete" || $action =="updateOrNew") {
                
                if (is_array($passData)) {
                    
                    if ($action=="delete") {  //Pass Button Id Array ["Id1","Id2","Id3"]
    
                        //not array check
                        
                        $r=self::trimArrayByObjectKeyValue($buttonIdMsgArray, $passData, "buttonNo");
                        if ($r->result=="success") {
                            $r1=DeviceList::where('user_id', $user_id)
                            ->where('device_id', $device_id)
                            ->update(['info' => $r->newArray]);
                            if ($r1) {
                                return (Object)["result"=>"success", "data"=>
                                DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                                ->where("user_id", "=",$user_id )->where("device_id", "=", $device_id)->get()
                                ];
                            }else{
                                return (Object)["result"=>"fail", "data"=>[]];
                            }
                            
                        }else{
                            return (Object)["result"=>$r->result, "data"=>[]];
                        }
    
                    } elseif($action=="updateOrNew") {//Pass Button Id Array [{"buttonNo": "ewq","message": "ewq"}]
                        
                        $r=self::addOrUpdateObjectInArray($buttonIdMsgArray, $passData,"buttonNo", ["buttonNo", "message"], ["buttonNo", "message"]);
                        if ($r->result=="success") {
                            $r1=DeviceList::where('user_id', $user_id)
                            ->where('device_id', $device_id)
                            ->update(['info' => $r->newArray]);
                            if ($r1) {
                                return (Object)["result"=>"success", "data"=>
                                DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                                ->where("user_id", "=",$user_id )->where("device_id", "=", $device_id)->get()
                                ];
                            }else{
                                return (Object)["result"=>"fail", "data"=>[]];
                            }
                            
                        }else{
                            return (Object)["result"=>$r->result, "data"=>[]];
                        }
    
                    }
                }
                return (Object)['result'=>'not-valid-array', 'data'=>[]];
            }
            return (Object)['result'=>'invalid-command', 'data'=>[]];
            
        }
        return (Object)['result'=>'no-privilege', 'data'=>[]];

    }

    public static function trimArrayByObjectKeyValue($oldArray, $ValueToTrimArray, $key){
        //$newArray=$oldArray;
        if (is_array($ValueToTrimArray)) {
            if (self::ArrayItemisString($ValueToTrimArray)) {
                $newArray=[];
                for ($i=0; $i < count($oldArray); $i++) { 
                    $val=$oldArray[$i][$key];
        
                    if (!in_array($val, $ValueToTrimArray)) {
                        array_push($newArray,$oldArray[$i]);
                    }
                }
                return (Object)["result"=>"success", "newArray"=>$newArray ];
            }
            return (Object)["result"=>"non-valid-array", "newArray"=>[] ];
        }
        return (Object)["result"=>"non-valid-array", "newArray"=>[] ];
    }

    public static function addOrUpdateObjectInArray($oldArray, $ObjectArrayUpdateOrCreate,$existDetermineKey, $requiredUpdateKey, $keyAllowedArray){
        /*if (self::ArrayObjectKeyValueExist($oldArray, $existDetermineKey ,  )) {
            # code...
        }*/
        $newArray=$oldArray;
        //print_r($ObjectArrayUpdateOrCreate);
        if(self::ObjectInArrayValid($ObjectArrayUpdateOrCreate, $requiredUpdateKey)){
            
            for ($i=0; $i < count($ObjectArrayUpdateOrCreate); $i++) { 
                $object=$ObjectArrayUpdateOrCreate[$i];
                foreach ($object as $key1 => $value) {
                    if (!in_array($key1, $keyAllowedArray)) {
                        unset($object[$key1]);
                    }
                }
                    if (self::ArrayObjectKeyValueExist($newArray, $existDetermineKey, $object[$existDetermineKey])) {
                        //try {
                            $newArray=self::ArrayUpdateObject($newArray, $existDetermineKey , $object[$existDetermineKey], $object);
                        //} catch (\Throwable $th) {
                            //throw $th;
                        //}
                       
                    }else{
                        //try {
                            $newArray=self::ArrayPushNewObject($newArray, $object);
                        //} catch (\Throwable $th) {
                            //throw $th;
                        //}
                        
                    }
            }
            return (Object)["result"=>"success", "newArray"=> $newArray];
        }
        return (Object)["result"=>"non-valid-array", "newArray"=> []];
    }

    public static function ArrayObjectKeyValueExist($array, $keyName, $keyValue){
        if (is_array($array)) {
            
                
            for ($i=0; $i < count($array); $i++) { 
                if (is_array($array[$i])) {
                    if (array_key_exists($keyName, $array[$i])) {
                        if ($array[$i][$keyName]==$keyValue) {
                            return true;
                        }
                    }
                    
                }
            }
        }
        return false;
    }

    public static function ArrayPushNewObject($oldArray, $ObjectToPush){
        $newArray=$oldArray;
        array_push($newArray, $ObjectToPush);
        return $newArray;
    }
    
    public static function ArrayUpdateObject($oldArray, $conditionKeyName, $conditionKeyValue, $ObjectToReassign){ //$updateDict: ["field1"=>"kkkkk", "field2"=>"lll"]
        //$newArray=[];
        $newArray=$oldArray;

        for ($i=0; $i < count($newArray); $i++) { 
            if($oldArray[$i][$conditionKeyName]==$conditionKeyValue){
                foreach ($ObjectToReassign as $key => $value) {
                    //echo $key." ".$value." ";
                    if ($conditionKeyName != $key) {
                        $newArray[$i][$key]=$value;
                    }
                }
            }
        }
        return $newArray;

    }
    //ObjectInArrayValid($array, $requiredKeyArray)
    public static function ObjectInArrayValid($array, $requiredKeyArray){
       
        if (is_array($array)) {
           
            for ($i=0; $i < count($array); $i++) { 
                
                if (is_array($array[$i])) {
                    
                    $object=$array[$i];
                    
                    for ($j=0; $j < count($requiredKeyArray); $j++) { 
                        if (!array_key_exists($requiredKeyArray[$j], $object)) {
                            return false;
                        }
                    }
                }else{
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    public static function ArrayItemisString($array){
        for ($i=0; $i < count($array); $i++) { 
            
            if (!is_string($array[$i])) {
                return false;
            }
        }
        return true;
    }

}


   