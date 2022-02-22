<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DeviceController;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\DeviceList;
use App\Models\User;
use App\Models\MessageEnquiry;
use App\Models\DeviceOwnershipShare;
use Illuminate\Support\Facades\Gate;
use App\Library\Services\DeviceRightService;
use App\Rules\DeviceShareValidEmail;
use App\Library\Services\BasicUserRelatedInfoService as BasicInfoService;
use App\Library\Services\MessageService;
use App\Http\Controllers\DeviceOwnershipShareController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DeviceAPIController;
use App\Http\Controllers\MobileAccessController;
use App\Http\Controllers\MobileAPIController;
use App\Http\Controllers\SubscriptionManagementController;

use App\Mail\DeviceCreated;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    //$stripeCustomer = $user->createAsStripeCustomer();
    //return  User::find(Auth::id())->deviceSharedToMe;
    //return  User::find(Auth::id())->deviceSharedToMe;
    //return User::find(Auth::id())->messageOfMyDevice()->orderBy('datetime')->get();
    
    return view('welcome');
});

Route::get('/doc', function () {
    return view('documentation');
});
Route::get('/test', function (Request $request) {
    //return Auth::user();
    return new \App\Mail\DeviceShared("sharee", 1, 2, 'ebaefc98-80bd-4e61-917d-9187b0e60b9b');

    $deviceCredential=(Object)[
        'nickname'=>'nnn',
        'deviceId'=>'1a7e96e0-730d-45c3-a3b9-e599d0a184c1',
        'bearerToken'=>'dev_b3oGnEcdYE0aaUtCjPsgAbOmJQ7h2GoVDOndD5XD'
    ];
  
        Mail::to($request->user())
        ->send(new App\Mail\DeviceCreated(Auth::user()->name, true, $deviceCredential));
   

    return new App\Mail\DeviceCreated(Auth::user()->name, true, $deviceCredential);

    //Mail::to($request->user())->send(new DeviceCreated());
    //return BasicInfoService::deviceList()->number;
    //return "test";
    //subscription_status_updated
    /*$all=User::all();
    for ($i=0; $i < count($all); $i++) { 
        $userId=$all[$i]->id;
        User::where('id', '=', $userId)->update(['subscription_status_updated'=>'false']);
    }*/
})->withoutMiddleware([VerifyCsrfToken::class]);
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::middleware(['subscription'])->group(function(){
        Route::get('/dashboard', function(){return view('dashboard', ['data'=>BasicInfoService::forDashboard()]);})->name('dashboard');
        Route::get('/dashboard/deviceList', [DeviceController::class, 'device_list_table'])->name('deviceList');
        //Mobile Access Controller
        Route::get('/mobileAccessList',[MobileAccessController::class, 'mobile_access_list'] )->name('mobile_access_list');
        Route::post('/mobile_access_new',[MobileAccessController::class, 'mobile_access_new'] )->name('mobile_access_new');
        Route::get('/mobileAccessList/{case_id?}',[MobileAccessController::class, 'mobile_access_individual'] )->name('mobile_access_individual');
        Route::post('/mobile_access_amend/{case_id?}',[MobileAccessController::class, 'mobile_access_amend'] )->name('mobile_access_amend');
        Route::post('/mobile_access_destroy/{case_id?}',[MobileAccessController::class, 'mobile_access_destroy'] )->name('mobile_access_destroy');
        Route::post('/mobile_access_settings/{case_id?}', [MobileAccessController::class, 'mobile_device_list_query'])->name('mobile_device_list_query')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::get('/dashboard/deviceList/{device_id?}', [DeviceController::class, 'individual_device_view'])->name('individual_device');
        Route::get('/dashboard/deviceOwnership/{device_id?}',[DeviceOwnershipShareController::class, 'individual_device_ownership_view'] )->name('individual_device_ownership');
        Route::post('/device_amend', [DeviceController::class, 'device_amend']);
        Route::post('/device_list_action', [DeviceController::class, 'device_list_action']);
        Route::post('/new_device', [DeviceController::class, 'new_device']);
        Route::post('/new_device_two_method', [DeviceController::class, 'new_device_two_method'])->name('new_device_two_method')->withoutMiddleware([VerifyCsrfToken::class]);

        Route::get('/dashboard/deviceSharedToMe',[DeviceOwnershipShareController::class, 'fetchTable'] )->name('deviceSharedToMe');
        Route::get('/dashboard/newDeviceWizard', [DeviceController::class, 'newDeviceWizard'])->name('newDeviceWizard');
        Route::post('/save_device_credential', [DeviceController::class, 'save_device_credential'])->name('save_device_credential');

        Route::get('/dashboard/message', [MessageController::class, 'msg_dashboard_ui'] )->name('message'); //ok
       
        Route::post('/device_share_revoke', [DeviceOwnershipShareController::class, 'device_share_revoke']);
        Route::post('/change_right_to/{case_id}',[DeviceOwnershipShareController::class, 'change_right'] );
        Route::post('/give_up_shared_right', [DeviceOwnershipShareController::class, 'give_up_shared_right']);
        Route::post('/device_share_add/{device_id}',[DeviceOwnershipShareController::class, 'device_share_add']);
    
       
    });
     //Message Retrieve For Console (All Subs Protected)
     Route::post('/msg_enquiry/{login_session?}', [MessageController::class, 'msg_enquiry'])->name('msg_enquiry_api')->withoutMiddleware([VerifyCsrfToken::class]);
     Route::post('/msg_enquiry_del/{login_session?}/{message_id?}',[MessageController::class, 'msg_delete'] )->name('msg_delete_api')->withoutMiddleware([VerifyCsrfToken::class]);
     Route::post('/msg_enquiry_pin/{login_session?}/{message_id?}/{true_false?}', [MessageController::class, 'msg_pin'] )->name('msg_pin_api')->withoutMiddleware([VerifyCsrfToken::class]);
 
 
    /*Route::get('dashboard/billing-portal', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('subscription_dashboard_ui'));
    });*/
    //Route::get('/user/subscribe', function (Request $request) {
        /*$request->user()->newSubscription(
            'default', 'price_monthly'
        )->create($request->paymentMethodId);*/
        // ...
    //});
    //Route::get('/test1', function () {
    //    return Auth::check();
    //});
    
     
   
    //Subscription (No need subs protection)
    Route::get('/dashboard/subscription', [SubscriptionManagementController::class, 'subscription_dashboard_ui'])->name('subscription_dashboard_ui');
    Route::get('/add_new_setup_intent', [SubscriptionManagementController::class, 'add_new_setup_intent'])->name('add_new_setup_intent');
    Route::post('/delete_payment_method', [SubscriptionManagementController::class, 'delete_payment_method'])->name('delete_payment_method');
    Route::post('/set_default_payment_method', [SubscriptionManagementController::class, 'set_default_payment_method'])->name('set_default_payment_method');
    Route::post('/update_payment_method', [SubscriptionManagementController::class, 'paymentMethodUpdate'])->name('update_payment_method');
    Route::post('/change_plan', [SubscriptionManagementController::class, 'changePlan'])->name('change_plan');
    Route::post('/subscribe_service', [SubscriptionManagementController::class, 'subscribe_service'])->name('subscribe_service');
    Route::post('/cancel_subscription/{SubId}', [SubscriptionManagementController::class, 'cancelSubscriptionItem'])->name('cancelSubscriptionItem');

    //
    
});



//device api
//To launch request, Query Param button_id need to be given and Bearer Token need to be given. Link: deviceAPI/{device_id}
Route::post('/deviceAPI/{device_id?}', [DeviceAPIController::class, 'push_msg'])->withoutMiddleware([VerifyCsrfToken::class]);//Subs Protected



Route::post('/mobileAPI/{access_token?}/{phone_token?}', [MobileAPIController::class, 'fetch_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected 
Route::post('/mobileAPIDeleteMessage/{access_token?}/{phone_token?}/{msg_id?}', [MobileAPIController::class, 'delete_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/mobileAPIPinMessage/{access_token?}/{phone_token?}/{msg_id?}/{true_false?}', [MobileAPIController::class, 'pin_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/mobileAPIValidCheck/{access_token?}/{phone_token?}', [MobileAPIController::class, 'AccessTokenValidCheck'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/mobileConnect/{access_token?}/{randStrFromPhone?}', [MobileAPIController::class, 'mobileConnect'])->withoutMiddleware([VerifyCsrfToken::class]);//no need
Route::post('/mobileBasicData/{access_token?}/{phone_token?}', [MobileAPIController::class, 'basic_data'])->withoutMiddleware([VerifyCsrfToken::class]);//no need
Route::post('/mobileDisconnect/{access_token?}', [MobileAPIController::class, 'mobileDisconnect'])->withoutMiddleware([VerifyCsrfToken::class]);//no need


Route::post('/mobileAPIDeviceList/{access_token?}/{phone_token?}/{device_id?}', [MobileAPIController::class, 'fetch_device_list'])->withoutMiddleware([VerifyCsrfToken::class]);//protected


