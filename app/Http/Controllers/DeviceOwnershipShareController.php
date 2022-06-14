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
use Illuminate\Support\Facades\Mail;
use App\Library\Services\DeviceShareService;

class DeviceOwnershipShareController extends Controller
{
    //
    public function fetchTable(DeviceList $deviceList){
        $userId=Auth::id();
        $data=DeviceOwnershipShare::where("share_to_user_id", "=", Auth::id())//->where('device_list.status', '='."'active'")
        ->join('device_list', 'device_list.device_id', '=', 'device_ownership_share.device_id')
        ->join('users', 'device_list.user_id', '=', 'users.id')
        ->select('device_ownership_share.case_id','device_ownership_share.device_id','device_ownership_share.share_to_user_id','device_ownership_share.created_time'
        ,'device_list.nickname','device_list.status', 'device_list.info', 'device_ownership_share.right', 'users.name as owner_name','users.email as owner_email')
        ->get();
        
        return view('dashboard.buttonDevice.sharedToMe', ['data'=>$data]);
    }

    public function device_share_revoke(Request $request) {
        $validator=Validator::make($request->all(), [
            'revoke' => 'required', //non editable
        ]);            
        $validated= $validator->validate();
        //
        /*$data=DeviceOwnershipShare::where("case_id",'=', $validated['revoke'])->get();
        $device_id=$data[0]->device_id;
        if(DeviceRightService::AbsoluteRightOnDevice($device_id)){
            DeviceOwnershipShare::where('device_id', '=', $device_id)->where('case_id', '=', $validated['revoke'])->delete();
        }*/
        DeviceShareService::revokeShareeRight(Auth::id(),  $validated['revoke']);
        //
        return redirect(url()->previous());
    }

    public function give_up_shared_right(Request $request){
        $validator=Validator::make($request->all(), [
            'give_up' => 'required', //non editable
        ]);            
        $validated= $validator->validate();
        $case_id=$validated['give_up'];
        /*DeviceOwnershipShare::where('share_to_user_id', '=', Auth::id()) // ensure the right
        ->where('case_id', '=', $case_id)->delete();*/
        DeviceShareService::GiveUpShareeRight(Auth::id(), $case_id);
        return redirect(url()->previous());
    
    }
    public function device_share_add(Request $request, $device_id) {
        
        //////////
        $validator=Validator::make($request->all(), [
            'email' => ['required','email',new DeviceShareValidEmail($device_id)], //non editable
        ]);            
        $validated= $validator->validate();
      
        DeviceShareService::ShareNewDevice(Auth::id(), $validated['email'], $device_id);
        //////////
        
        return redirect(url()->previous());
    }
    public function change_right(Request $request, $case_id) {
    
        $validator=Validator::make($request->all(), [
            'right_alter' => 'required', //non editable
        ]);        
        
        $validated= $validator->validate();
        //
        /*$data=DeviceOwnershipShare::where("case_id",'=', $case_id)->get();
        
        $device_id=$data[0]->device_id;
        if(DeviceRightService::AbsoluteRightOnDevice($device_id)){//Protection
            $new_right=$validated['right_alter'];
            DeviceOwnershipShare::where('device_id', '=', $device_id)->where('case_id', '=', $case_id)->update(['right'=>$new_right]);
        }*/
        DeviceShareService::changeShareeRight(Auth::id(), $case_id, $validated['right_alter']);
        //
        return redirect(url()->previous());
    }
    public function individual_device_ownership_view($device_id,Request $request/*,DeviceController $dc*/) {
        $backRouteName=$request->query("back");

        if(Auth::check()){ //Ensure Authenticated
            $userId=Auth::id();
            $data=DeviceList::where('user_id', $userId)//Ensure the Right
                ->where('device_id', $device_id)
                ->get();
            if(count($data)==0){
                return view('dashboard.buttonDevice.ownershipSettings', ['result'=>'fail', 'data'=>[], 'device_id'=>[], 'device_nickname'=>[], 'backRouteName'=>$backRouteName]);

            }else{    
                $data1 =DeviceList::join('device_ownership_share', 'device_ownership_share.device_id', '=', 'device_list.device_id')
                ->join('users', 'users.id', '=', 'device_ownership_share.share_to_user_id')
                ->where('device_list.user_id','=', $userId)->where('device_list.device_id','=',$device_id)// Ensure the right
                ->select('device_ownership_share.case_id','device_ownership_share.share_to_user_id', 'users.email as share_to_email', 'device_ownership_share.created_time', 'device_ownership_share.right')->get();
                return view('dashboard.buttonDevice.ownershipSettings', ['result'=>'success', 'data'=>$data1, 'device_id'=>$device_id, 'device_nickname'=>$data[0]->nickname, 'backRouteName'=>$backRouteName]);
            }
        }else{
            return "xxx";
        }
    }
}
