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

///subscription middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('message')->group(function(){
        Route::post('fetch', function (Request $request){
            if ($request->user()->tokenCan('read')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    $data=MessageService::AllMessages($request->user()->id);
                    return response()->json(['result'=>'success', 'data'=>$data]);
                }
                return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
            }
            return response()->json(['result'=>'no-privilege', 'data'=>[]]);
        });
        Route::post('pin/{msgId?}', function (Request $request, $msgId){
            if ($request->user()->tokenCan('update')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'true'));
                }
                return response()->json(['result'=>'not-subscribed']);
            }
            return response()->json(['result'=>'no-privilege']);
        });
        Route::post('unpin/{msgId?}', function (Request $request, $msgId){
            if ($request->user()->tokenCan('update')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'false'));
                }
                return response()->json(['result'=>'not-subscribed']);
            }
            return response()->json(['result'=>'no-privilege']);
        });
        Route::post('delete/{msgId?}', function (Request $request, $msgId){
            if ($request->user()->tokenCan('update')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    return response()->json(MessageService::deleteMessage(Auth::id(), $msgId));
                }
                return response()->json(['result'=>'not-subscribed']);
            }
            return response()->json(['result'=>'no-privilege']);
        });
    });

    Route::prefix('buttonDevice')->group(function(){
        Route::post('myDevice/list/{device_id?}', function (Request $request, $device_id=null){
            if ($request->user()->tokenCan('read')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    if ($device_id==null) {
                        $data=DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')->where("user_id", "=",$request->user()->id )->get();
                    }
                    else{
                        $data=DeviceList::select('device_id', 'info', 'datetime', 'status', 'nickname', 'repeated_message')
                        ->where("user_id", "=",$request->user()->id )->where("device_id","=",$device_id)->get();
                    }
                   
                    return response()->json(['result'=>'success', 'data'=>$data]);
                }
                return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
            }
            return response()->json(['result'=>'no-privilege', 'data'=>[]]);
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
        

    });
    Route::prefix('mobileDevice')->group(function(){
    
        Route::post('fetch', function (Request $request){
            if ($request->user()->tokenCan('read')) {
                if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                    $data=MobileAccess::select('case_id', 'nickname','access_token', 'deleted_from_phone', 'last_access')->where('user_id', '=', Auth::id())->get();
                    return response()->json(['result'=>'success', 'data'=>$data]);
                }
                return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
            }
            return response()->json(['result'=>'no-privilege', 'data'=>[]]);
        });
        Route::post('new/{nickname}', function (Request $request, $nickname){
            if ($request->user()->tokenCan('read')) {
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
        });
        
       

    });
});

