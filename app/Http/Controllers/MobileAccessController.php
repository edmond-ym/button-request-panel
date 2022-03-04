<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Library\Services\UserRightOnMobileTokenService;
use App\Library\Services\MessageService;
use App\Library\Services\MobileAccessService;
class MobileAccessController extends Controller
{
    //
    
    public function mobile_access_new(Request $request){
        $validator=Validator::make($request->all(), [
            'nickname' => 'required|max:40|'
        ]);
    
        $validated= $validator->validate();
    
        if(!$validator->fails() && Auth::check()){
            MobileAccessService::NewMobileAccess(Auth::id(), $validated['nickname']);
        }
        
        return redirect(url()->previous());

    }
    public function mobile_access_list(){
        $data=MobileAccess::where('user_id', '=', Auth::id())->get();
        return view('dashboard.mobileAccessDevice.all', ['data'=>$data]);
    }
    public function mobile_device_list_query($case_id=null){
        if ($case_id==null) {
            $data=MobileAccess::where('user_id', '=', Auth::id())->get();//Ensure Right
        }else{
            $data=MobileAccess::where('user_id', '=', Auth::id())->where('case_id', '=', $case_id)->get();//Ensure Right
        }
       
        return $data;
    }
    public function mobile_access_individual($case_id){
        
        if (UserRightOnMobileTokenService::right($case_id)) {
            $data=MobileAccess::where('case_id', '=', $case_id)
            ->where('user_id', '=', Auth::id()) // ensure user right on that
            ->get();
            //$eligible_nick_name_list=MessageService::AllMessagesNicknameList(Auth::id());
            
            return view('dashboard.mobileAccessDevice.individualSettings', ['basic_info'=>$data[0]]);
        }
        return "No Privilege or Not Exist";

    }
    public function mobile_access_amend($case_id, Request $request){
        if (UserRightOnMobileTokenService::right($case_id)) {
            $validator=Validator::make($request->all(), [
                'nickname' => 'required|max:40|'
            ]);
        
            $validated= $validator->validate();
        
            if(!$validator->fails() && Auth::check()){
                MobileAccess::where('case_id', '=', $case_id)
                ->where('user_id', '=', Auth::id()) // ensure user right on that
                ->update(['nickname'=>$validated['nickname']]);
            }
        }
        return redirect(url()->previous());


    }
    public function mobile_access_destroy($case_id){
        //if (UserRightOnMobileTokenService::right($case_id)) {

            if(Auth::check()){
               /*MobileAccess::where('case_id', '=', $case_id)
                ->where('user_id', '=', Auth::id()) // ensure user right on that
                ->delete();*/
                MobileAccessService::MobileAccessDestroy(Auth::id(), $case_id);
            }
        //}
        return redirect(route('mobile_access_list'));
    }
}
