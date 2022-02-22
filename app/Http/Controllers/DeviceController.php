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
use Illuminate\Support\Facades\Mail;

use App\Mail\DeviceCreated;

class DeviceController extends Controller
{
    public function new_device_two_method(Request $request){
        
        
        $validator=Validator::make($request->all(), [
            'deviceId' => 'required|max:100|unique:device_list,device_id',
            'bearerToken' => 'required|max:100|unique:device_list,bearer_token',
            //'info' => 'required|max:65536',
            'nickname'=>'required|max:100',
            'newCredential'=>'required'
            //'save_this_key'=>'nullable'
        ]);
        
        if(!$validator->fails() && Auth::check()){
            $validated= $validator->validate();
            //return $validated['deviceId'];
            $r=DeviceList::insert([
                'case_id' => Str::random(10),
                'device_id' => $validated['deviceId'],
                'user_id' => Auth::id(),
                'bearer_token' => hash('sha256', $validated['bearerToken']),
                //'info' => $validated['info'],
                'info' => '[]',
                'datetime' =>  gmdate("Y-m-d H:i:s P"),
                'status' =>  'active', //active/suspend
                'nickname'=> $validated['nickname'],
                //'info_saved_for_later'=>$save_for_later
            ]);
            if ($r==1) {
                $deviceCredential=(Object)[
                    'nickname'=>$validated['nickname'],
                    'deviceId'=>$validated['deviceId'],
                    'bearerToken'=>$validated['bearerToken']
                ];
              
                Mail::to($request->user())->send(new DeviceCreated(Auth::user()->name, $validated['newCredential'], $deviceCredential));
               
                return ["result"=>"success", "errors"=>[]];
            } else {
                return ["result"=>"fail", "errors"=>[]];
            }
        }else{
           return ["result"=>"not-enough-info", "errors"=>$validator->errors()->all()];
        }
    }
    /*public function save_device_credential(Request $request){
        $validator=Validator::make($request->all(), [
            'deviceId' => 'required|max:100|unique:device_list,device_id',
            'bearerToken' => 'required|max:100|unique:device_list,bearer_token',
            'nickname'=>'required|max:100',
        ]);
        if(!$validator->fails() && Auth::check()){
            $validated= $validator->validate();
            
        }else{
           return ["result"=>"not-saved"];
        }
    }*/
    public function new_device(Request $request){
        
        $validator=Validator::make($request->all(), [
            'deviceId' => 'required|max:100|unique:device_list,device_id',
            'bearerToken' => 'required|max:100|unique:device_list,bearer_token',
            //'info' => 'required|max:65536',
            'nickname'=>'required|max:100',
            //'save_this_key'=>'nullable'
        ]);

        $validated= $validator->validate();
        

        if(!$validator->fails() && Auth::check()){
            /*if ($request->input('save_this_key')=="true") {
                $save_for_later=json_encode(["bearerToken"=>$validated['bearerToken']]);
            } else {
                $save_for_later=json_encode([]);
            }*/
            
            DeviceList::insert([
                'case_id' => Str::random(10),
                'device_id' => $validated['deviceId'],
                'user_id' => Auth::id(),
                'bearer_token' => hash('sha256', $validated['bearerToken']),
                //'info' => $validated['info'],
                'info' => '[]',
                'datetime' =>  gmdate("Y-m-d H:i:s P"),
                'status' =>  'active', //active/suspend
                'nickname'=> $validated['nickname'],
                //'info_saved_for_later'=>$save_for_later
            ]);
            return redirect()->route('deviceList');
        }else{
            return redirect()->route('deviceList');
        }
    }
    public function device_amend(Request $request){
        if(Auth::check()){
            $userId=Auth::id();
            $validator=Validator::make($request->all(), [
                'case_id' => ['required'], //non editable
                'device_id' => ['required'],//non editable
                'info' => ['required'],
                'nickname' => ['required','max:100'],
                //'no_repeated_msg'=>['accepted']
            ]);            
            
            $validated= $validator->validate();
      
            if(DeviceRightService::AbsoluteRightOnDevice($validated['device_id']) || DeviceRightService::SharedDeviceAdvancedRight(Auth::id(), $validated['device_id'])){//Ensure the Right
                DeviceList::where('case_id', $validated['case_id'])
                ->where('device_id', $validated['device_id'])
                ->update(['info' => $validated['info'], 'nickname' => $validated['nickname'], 'repeated_message'=>$request->input('repeated_msg')]);
            }
            return redirect()->route('individual_device', ['device_id' => $validated['device_id']]);

            
        }else{
            return redirect()->route('individual_device', ['device_id' => $validated['device_id']]);        
        }     
    }
    public function device_list_action(Request $request){
        if(Auth::check()){
            $userId=Auth::id();
            $validator=Validator::make($request->all(), [
                'action_submit'=>'required',
                'device_case' => ['required', new DeviceRevokeBarPass('action_submit', $request)],    
            ],
            [
                'device_case.required' => 'None is Selected',
            ]);
            $validated= $validator->validate();
            
            $type=$validated["action_submit"];
            $case_arr=$validated["device_case"];
            //return $case_arr;
            if($type=="active"){
                for ($i=0; $i < count($case_arr); $i++) { 
                    DeviceList::where('user_id', $userId)//Ensure the Right
                    ->where('case_id', $case_arr[$i])
                    ->update(['status' => "active"]);
                }
            }
            if($type=="suspend"){
                for ($i=0; $i < count($case_arr); $i++) { 
                    DeviceList::where('user_id', $userId)//Ensure the Right
                    ->where('case_id', $case_arr[$i])
                    ->update(['status' => "suspend"]);
                }
            }
            if($type=="revoke"){
                
                for ($i=0; $i < count($case_arr); $i++) { 
                    $device_id=DeviceList::CorrespondingDeviceId($case_arr[$i]);
                    if (DeviceRightService::AbsoluteRightOnDevice($device_id)) {
                        DeviceList::find($case_arr[$i])->messageEnquiry()->delete();
                        DeviceList::find($case_arr[$i])->deviceOwnership()->delete();
                        DeviceList::find($case_arr[$i])->delete();
                    }
                }
                //->
            }
            return redirect()->route('deviceList');
        }else{
            return redirect()->route('deviceList');        
        }     
    }
    public function individual_device_view($device_id,Request $request,DeviceController $dc, DeviceList $deviceList) {
        if(Auth::check()){ //Ensure Authenticated
            $userId=Auth::id();
            
            if(DeviceRightService::AbsoluteRightOnDevice($device_id) || DeviceRightService::SharedDeviceAdvancedRight(Auth::id(), $device_id)){//Ensure the Right
                $data=DeviceList::where('device_id', $device_id)->get();
                return view('IndividualDeviceData', ['result'=>'success','data'=>$data[0], 'info'=>($data[0]->info), 'info_len'=>count(json_decode($data[0]->info))]);
                
            }else{            
                return view('IndividualDeviceData', ['result'=>'fail', 'data'=>[], 'info'=>[], 'info_len'=>[]]);
            }
        }else{
            return "xxx";
        }
    }
    public function device_list_table(DeviceList $deviceList, $user_id=null) {
        if ($user_id==null) {
            $user_id=Auth::id();
        }
        $data=DeviceList::select('case_id','device_id', 'info', 'datetime', 'status', 'nickname')->where("user_id", "=",$user_id )->get();
        return view('deviceList', ['data'=>$data]);
    }

    public function newDeviceWizard(){
        return view('newDeviceWizard');
    }
}
