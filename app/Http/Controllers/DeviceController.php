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
use App\Library\Services\CommonService;
use Illuminate\Support\Facades\URL;

use App\Mail\DeviceCreated;

class DeviceController extends Controller
{
    public function new_device_two_method(Request $request){
        
        
        $validator=Validator::make($request->all(), [
            'deviceId' => 'required|max:100|unique:device_list,device_id|uuid',
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
                'bearer_token' => CommonService::DeviceAPIEncrypt($validated['bearerToken']),
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
                if (env('MAIL_ENABLED')) {
                    Mail::to($request->user())->send(new DeviceCreated(Auth::user()->name, $validated['newCredential'], $deviceCredential));
                }
                
               
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
                'bearer_token' => Common::DeviceAPIEncrypt($validated['bearerToken']) ,
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
           // return redirect()->route('individual_device', ['device_id' => $validated['device_id']]);
           return redirect(url()->previous());

            
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
        $backRouteName=$request->query("back");
        
        if(Auth::check()){ //Ensure Authenticated
            $userId=Auth::id();
            
            if(DeviceRightService::AbsoluteRightOnDevice($device_id) || DeviceRightService::SharedDeviceAdvancedRight(Auth::id(), $device_id)){//Ensure the Right
                $data=DeviceList::where('device_id', $device_id)->get();
                return view('dashboard.buttonDevice.deviceSettings', ['result'=>'success','data'=>$data[0], 'info'=>($data[0]->info), 'info_len'=>count(json_decode($data[0]->info)), 'backRouteName'=>$backRouteName]);
                
            }else{            
                return view('dashboard.buttonDevice.deviceSettings', ['result'=>'fail', 'data'=>[], 'info'=>[], 'info_len'=>[], 'backRouteName'=>$backRouteName]);
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
        return view('dashboard.buttonDevice.all', ['data'=>$data]);
    }

    public function newDeviceWizard(Request $request){
        $backRouteName=$request->query("back");
        return view('dashboard.buttonDevice.newDeviceWizard', ['backRouteName'=>$backRouteName]);
    }

    public function revealDeviceBearerToken(Request $request, $device_id){
        if (! $request->hasValidSignature()) {
            abort(401);
        }
        if (Auth::check()) {
            $data=DeviceList::select('device_id', 'bearer_token')
            ->where("user_id", "=",Auth::id() )    // ensure power
            ->where("device_id", "=", $device_id)
            ->get();
            if (count($data)>0) {
                $device_id=$data[0]->device_id;
                $bearerTokenEncrypted=$data[0]->bearer_token;
                $res=CommonService::DeviceAPIDecrypt($bearerTokenEncrypted);
                if ($res->result=="success") {
                    $decrypedBearerToken=$res->decryptedString;
                    return view('misc.revealDeviceBearerToken',
                    ["result"=>"success", "data"=>[
                        
                        "device_id"=>$device_id,"bearer_token"=>$decrypedBearerToken
                    ]]);
                   
                }else{
                    return view('misc.revealDeviceBearerToken',["result"=>"decryption-failed", "data"=>[]]);

                }
            }else{
                return view('misc.revealDeviceBearerToken',["result"=>"no-privilege",  "data"=>[]]);
            }
        }
        return view('misc.revealDeviceBearerToken',["result"=>"no-privilege",  "data"=>[]]);
        //return "a";
    }
    public function openRevealBearerTokenWindow($device_id){
        $data=DeviceList::select('device_id', 'bearer_token')
            ->where("user_id", "=",Auth::id() )    // ensure power
            ->where("device_id", "=", $device_id)
            ->get();
        if (count($data)>0) {
            
            $temUrl=URL::temporarySignedRoute(
                'revealDeviceBearerToken', now()->addMinutes(5),['device_id' => $device_id]
            );
            return redirect($temUrl);
           
            
        }
        
        
        
    }
}
