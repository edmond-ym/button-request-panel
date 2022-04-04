<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Library\Services\MessageService;
use App\Library\Services\SubscriptionManagementService;
use App\Http\Controllers\MessageController;
use App\Models\DeviceList;
use App\Library\Services\DeviceRightService;
use App\Models\MobileAccess;
use App\Library\Services\MobileAccessService;
use App\Library\Services\APIQueryService;
use App\Library\Services\DeviceListService;
use App\Models\DeviceOwnershipShare;
use App\Models\User;
use App\Library\Services\DeviceShareService;

//use App\Library\Services\APIBasicTest;
//use App\Http\Middleware\ParameterAllFilled;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('apiKeyTest', function (Request $request){
    $data=DB::table('personal_access_tokens')->where('token', '=',hash('sha256',$request->bearerToken()))->get();
    
    if (count($data)==1) {
        if (Auth::loginUsingId($data[0]->tokenable_id)) {
            //return $data[0];
            return response()->json(['result'=>'valid', 
            'data'=>[
                'userId'=>$request->user()->id,
                'subscribed'=>SubscriptionManagementService::offlineStatusSubscribed($request->user()->id), 
                'right'=>[
                    'create'=>in_array("create", json_decode($data[0]->abilities)) ? 'yes' : 'no',
                    'read'=>in_array("read", json_decode($data[0]->abilities)) ? 'yes' : 'no',
                    'update'=>in_array("update", json_decode($data[0]->abilities)) ? 'yes' : 'no',
                    'delete'=>in_array("delete", json_decode($data[0]->abilities)) ? 'yes' : 'no'
                ]
            ]]);
        }
        return response()->json(['result'=>'invalid', 'data'=>[]]);
    }
    return response()->json(['result'=>'invalid', 'data'=>[]]);
   
});

///subscription middleware
Route::middleware(['auth:sanctum', 'apiAuth'])->group(function () {
    Route::prefix('message')->group(function(){
        Route::any('fetch', function (Request $request){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('read')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $data=MessageService::AllMessages($request->user()->id);
                        return response()->json(['result'=>'success', 'data'=>$data]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });
        Route::any('pinStatus/{msgId?}/{action?}', function (Request $request, $msgId=null, $action=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('update')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        
                        if ($action=="unpin") {
                            return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'false'));
                        }elseif ($action=="pin") {
                            return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'true'));
                        }else{
                            return response()->json(['result'=>'invalid-action']);
                        }
                    }
                    return response()->json(['result'=>'not-subscribed']);
                }
                return response()->json(['result'=>'no-privilege']);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        
        Route::any('delete/{msgId?}', function (Request $request, $msgId=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('delete')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        return response()->json(MessageService::deleteMessage(Auth::id(), $msgId));
                    }
                    return response()->json(['result'=>'not-subscribed']);
                }
                return response()->json(['result'=>'no-privilege']);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });
    });

    Route::prefix('buttonDevice')->group(function(){
        Route::any('myDevice/list/{device_id?}', function (Request $request, $device_id=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('read')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        if ($device_id==null) {
                            $data=DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                            ->where("user_id", "=",$request->user()->id )->get();
                        }
                        else{
                            $data=DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                            ->where("user_id", "=",$request->user()->id )->where("device_id","=",$device_id)->get();
                        }
                        for ($i=0; $i < count($data); $i++) { 
                            $data[$i]['OwnershipShare']=DeviceOwnershipShare::join('users', 'users.id', '=', 'device_ownership_share.share_to_user_id')
                            ->select("device_ownership_share.case_id AS case_id", "users.name", "users.email", "right", "created_time")->where('device_id', '=', $data[$i]['device_id'])->get();
                        }
                        return response()->json(['result'=>'success', 'data'=>$data]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        Route::any('myDevice/repeatedMessage/{device_id?}/{action?}', function (Request $request, $device_id=null, $action=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('update')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        if ($action=="allow") {
                            $r=DeviceListService::repeatMessageAllowUpdate($request->user()->id,$device_id, $action);
                            return response()->json(['result'=>$r->result, 'data'=>$r->data]);
                        }elseif($action=="disallow"){
                            $r=DeviceListService::repeatMessageAllowUpdate($request->user()->id,$device_id, $action);
                            return response()->json(['result'=>$r->result, 'data'=>$r->data]);
                        }
                        return response()->json(['result'=>'invalid-command', 'data'=>[]]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });

        Route::any('myDevice/buttonMessageUpdate/{action?}/{device_id?}', function (Request $request, $action=null,$device_id=null){
            if ($request->isMethod('post')) {
                
            
                if ($request->user()->tokenCan('update')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        /*$a=json_decode('
                        [
                            {
                                "buttonNo": "ewq",
                                "message": "ewq1"
                            },
                            {
                                "buttonNo": "dew",
                                "message": "dsw"
                            },
                            {
                                "buttonNo": "dew1j2nj2jj2",
                                "message": "dsw"
                            }
                        ]'
                        , true);
                        $b=json_decode('
                        [
                            "ewq"
                        ]'
                        , true);*/  
                        if ($request->has('passData')) {
                            if(is_array(json_decode($request->input('passData'), true))){
                                if (array_key_exists('dataArray', json_decode($request->input('passData'), true))) {
                                    $r=DeviceListService::buttonMessageConfigure(Auth::id(), $device_id, $action, json_decode($request->input('passData'), true)['dataArray']);
                                    return response()->json(['result'=>$r->result, 'data'=>$r->data]);
                                }else{
                                    return response()->json(['result'=>'dataArray-key-missing', 'data'=>[]]);
                                }
                            }
                            return response()->json(['result'=>'passData-not-object', 'data'=>[]]);
                        }else{
                            return response()->json(['result'=>'passData-body-key-missing', 'data'=>[]]);
                        }
    
                        //return response()->json(['result'=>'invalid-command', 'data'=>[]]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });
        
        Route::any('deviceSharedToMe/fetch', function (Request $request){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('read')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $data=DeviceOwnershipShare::where("share_to_user_id", "=", Auth::id())//->where('device_list.status', '='."'active'")
                        ->join('device_list', 'device_list.device_id', '=', 'device_ownership_share.device_id')
                        ->join('users', 'device_list.user_id', '=', 'users.id')
                        ->select('device_ownership_share.case_id','device_ownership_share.device_id','device_ownership_share.share_to_user_id','device_ownership_share.created_time'
                        ,'device_list.nickname','device_list.status', 'device_list.info', 'device_ownership_share.right', 'users.name as owner_name','users.email as owner_email')
                        ->get();
                        return response()->json(['result'=>'success', 'data'=>$data]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);  
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        Route::any('deviceSharedToMe/delete/{case_id?}', function (Request $request, $case_id=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('delete')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                       $r=DeviceShareService::GiveUpShareeRight($request->user()->id, $case_id);
                       return response()->json(['result'=>$r->result, 'data'=>[["case_id"=>$r->case_id]]]);
                    }
                    return response()->json(['result'=>'not-subscribed','data'=>[["case_id"=>$r->case_id]]]);
                }
               return response()->json(['result'=>'no-privilege', 'data'=>[["case_id"=>$r->case_id]]]); 
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });
        Route::any('shareDevice/new/{email?}/{device_id?}', function (Request $request, $email=null, $device_id=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('create')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $r=DeviceShareService::ShareNewDevice($request->user()->id, $email, $device_id);
                        if($r->result=="success"){
                            return response()->json(['result'=>$r->result, 'data'=>
                                DeviceOwnershipShare::join('users', 'users.id', '=', 'device_ownership_share.share_to_user_id')
                                ->select("users.name", "users.email", "right","device_id", "case_id", "created_time")->where('device_id', '=', $device_id)->get()
                            ]);
                        }
                        return response()->json(['result'=>$r->result,'data'=>[]]);
                        
                    }
                    return response()->json(['result'=>'not-subscribed','data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        Route::any('shareDevice/changeRight/{case_id?}/{right?}', function (Request $request, $case_id=null, $right=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('update')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        
                        $r=DeviceShareService::changeShareeRight(Auth::id(), $case_id, $right);
                        if ($r->result=="success") {
                            return response()->json(['result'=>$r->result,'data'=>
                                DeviceOwnershipShare::join('users', 'users.id', '=', 'device_ownership_share.share_to_user_id')
                                ->select("users.name", "users.email", "right","device_id", "case_id", "created_time")->where('case_id', '=', $case_id)->get()
                            ]);
                        }
                        return response()->json(['result'=>$r->result,'data'=>[]]);
                    }
                    return response()->json(['result'=>'not-subscribed','data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });

        Route::any('shareDevice/revokeSharee/{case_id}', function (Request $request, $case_id){
 
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('delete')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $r=DeviceShareService::revokeShareeRight($request->user()->id,  $case_id);
                        if ($r->result=="success") {
                            return response()->json(['result'=>$r->result,'data'=>[]]);
                        }
                        return response()->json(['result'=>$r->result,'data'=>[]]);
                    }
                    return response()->json(['result'=>'not-subscribed','data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });

        

        /*Route::post('myDevice/update/{device_id}', function (Request $request, $device_id){
            if ($request->user()->tokenCan('read')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {

                    if (DeviceRightService::AbsoluteRightOnDevice($device_id, $request->user()->id)) {
                        
                    }
                    return response()->json(['result'=>'no-privilege', 'data'=>[]]);
                }
                return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
            }
            return response()->json(['result'=>'no-privilege', 'data'=>[]]);
        });*/
        Route::any('myDevice/new/{nickname?}', function (Request $request, $nickname=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('create')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        
                        $rObj=DeviceListService::newDeviceGenerate($request->user()->id, $nickname);
                        return response()->json(['result'=>$rObj->result, 'data'=>[$rObj->data]]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]); 
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        

    });
    Route::prefix('mobileDevice')->group(function(){
        Route::any('fetch', function (Request $request){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('read')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $data=MobileAccess::select('case_id', 'nickname','access_token', 'deleted_from_phone', 'last_access')->where('user_id', '=', Auth::id())->get();
                        return response()->json(['result'=>'success', 'data'=>$data]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        Route::any('new/{nickname?}', function (Request $request, $nickname=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('create')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        $rObj=MobileAccessService::NewMobileAccess($request->user()->id, $nickname);
                        if ($rObj->result=="success") {
                            $data=MobileAccess::select('case_id', 'nickname','access_token', 'deleted_from_phone', 'last_access')->where('user_id', '=', Auth::id())->where('case_id','=', $rObj->case_id)->get();
                            return response()->json(['result'=>'success', 'data'=>$data]);
                        }
                        return response()->json(['result'=>'fail', 'data'=>[]]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });
        Route::any('revoke/{case_id?}', function (Request $request, $case_id=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('delete')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        
                        $rObj=MobileAccessService::MobileAccessDestroy($request->user()->id, $case_id);
                        return response()->json(['result'=>$rObj->result, 'data'=>[['case_id'=>$rObj->case_id]]]);
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
        });

        Route::any('amend/{case_id?}', function (Request $request, $case_id=null){
            if ($request->isMethod('post')) {
                if ($request->user()->tokenCan('update')) {
                    if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                        //$a=new APIQueryService(["nickname"=>"nick_name", "key"=>"k_e_y"]);
                        $a=new APIQueryService(["nickname"=>"nickname"]); 
                        
    
                        $rObj=MobileAccessService::MobileAccessAmend($request->user()->id, $case_id, $a->toDBUpdateArray($request->input()));
    
                        return response()->json(['result'=>$rObj->result, 'data'=>$rObj->data]);
    
                    }
                    return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
                }
                return response()->json(['result'=>'no-privilege', 'data'=>[]]);
            }else{
                return response()->json(['result'=>'not-post-method']);
            }
            
        });

    });
});