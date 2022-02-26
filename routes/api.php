<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Library\Services\MessageService;
use App\Library\Services\SubscriptionManagementService;
use App\Http\Controllers\MessageController;
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
    Route::post('v1/message/fetch', function (Request $request){
        if ($request->user()->tokenCan('read')) {
            if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                $data=MessageService::AllMessages($request->user()->id);
                return response()->json(['result'=>'success', 'data'=>$data]);
            }
            return response()->json(['result'=>'not-subscribed', 'data'=>[]]);
        }
        return response()->json(['result'=>'no-privilege', 'data'=>[]]);
    });
    Route::post('v1/message/pin/{msgId?}', function (Request $request, $msgId){
        if ($request->user()->tokenCan('update')) {
            if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'true'));
            }
            return response()->json(['result'=>'not-subscribed']);
        }
        return response()->json(['result'=>'no-privilege']);
    });
    Route::post('v1/message/unpin/{msgId?}', function (Request $request, $msgId){
        if ($request->user()->tokenCan('update')) {
            if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                return response()->json(MessageService::pinMessage(Auth::id(), $msgId, 'false'));
            }
            return response()->json(['result'=>'not-subscribed']);
        }
        return response()->json(['result'=>'no-privilege']);
    });
    Route::post('v1/message/delete/{msgId?}', function (Request $request, $msgId){
        if ($request->user()->tokenCan('update')) {
            if (SubscriptionManagementService::offlineStatusSubscribed($request->user()->id)) {
                return response()->json(MessageService::deleteMessage(Auth::id(), $msgId));
            }
            return response()->json(['result'=>'not-subscribed']);
        }
        return response()->json(['result'=>'no-privilege']);
    });
});

