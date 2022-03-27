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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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


/*Route::get('/2', function () {
    //$stripeCustomer = $user->createAsStripeCustomer();
    //return  User::find(Auth::id())->deviceSharedToMe;
    //return  User::find(Auth::id())->deviceSharedToMe;
    //return User::find(Auth::id())->messageOfMyDevice()->orderBy('datetime')->get();
    
    //return view('welcome');
    //return "jjj";
    echo $_SERVER['SERVER_NAME'] ;//. $_SERVER['REQUEST_URI'];
    //return view('home');

})->name('home');*/

Route::get('/doc1', function () {
    return view('documentation1');
});



Route::get('/test', function (Request $request) {
    //return "an";
    //$a1= DeviceList::where("device_id",'=', '244a5a0e-3d0c-402f-9191-c2a3dd57d2ec')->get()[0]->bearer_token;
    //return URL::signedRoute('revealDeviceBearerToken', ['user' => 1]);
    //$token = $request->user()->createToken("jjj");
    //$a="dev_2Z6jg80bMm42l374j0g6plWo3sd3GFq15UfzcfyB";
     
    //$a1=Crypt::encryptString($a); echo $a1;
    //echo "t1";
    /*try {
        $decrypted = Crypt::decryptString($a1);
        echo $decrypted;
    } catch (DecryptException $e) {
        //
    }*/
    //return Crypt::encryptString("dev_2Z6jg80bMm42l374j0g6plWo3sd3GFq15UfzcfyB");
    //return ['token' => $token->plainTextToken];
  // return file_get_contents(asset('public/apiDoc/data.yaml'));
   // use Yaml;
   // $yamlContents = Yaml::parse(file_get_contents(''));
    /*$deviceCredential=(Object)[
        'nickname'=>'nnn',
        'deviceId'=>'1a7e96e0-730d-45c3-a3b9-e599d0a184c1',
        'bearerToken'=>'dev_b3oGnEcdYE0aaUtCjPsgAbOmJQ7h2GoVDOndD5XD'
    ];
    return new App\Mail\DeviceCreated(Auth::user()->name, true, $deviceCredential);
    Mail::to($request->user())
    ->send(new App\Mail\DeviceCreated(Auth::user()->name, true, $deviceCredential));*/
    return "";
   
})->withoutMiddleware([VerifyCsrfToken::class]);
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::middleware(['subscription'])->group(function(){
        //Route::get
        Route::get('/reveal-device-bearer-token/{device_id}', [DeviceController::class, 'revealDeviceBearerToken'])->name('revealDeviceBearerToken');

        Route::get('/', function(){ return view('dashboard.home', ['data'=>BasicInfoService::forDashboard()]);})->name('dashboard');
        Route::get('/deviceList', [DeviceController::class, 'device_list_table'])->name('deviceList');
        //Mobile Access Controller
        Route::get('/mobileAccessList',[MobileAccessController::class, 'mobile_access_list'] )->name('mobile_access_list');
        Route::post('/mobile_access_new',[MobileAccessController::class, 'mobile_access_new'] )->name('mobile_access_new');
        Route::get('/mobileAccessList/{case_id?}',[MobileAccessController::class, 'mobile_access_individual'] )->name('mobile_access_individual');
        Route::post('/mobile_access_amend/{case_id?}',[MobileAccessController::class, 'mobile_access_amend'] )->name('mobile_access_amend');
        Route::post('/mobile_access_destroy/{case_id?}',[MobileAccessController::class, 'mobile_access_destroy'] )->name('mobile_access_destroy');
        Route::post('/mobile_access_settings/{case_id?}', [MobileAccessController::class, 'mobile_device_list_query'])->name('mobile_device_list_query')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::get('/deviceList/{device_id?}', [DeviceController::class, 'individual_device_view'])->name('individual_device');
        Route::get('/deviceOwnership/{device_id?}',[DeviceOwnershipShareController::class, 'individual_device_ownership_view'] )->name('individual_device_ownership');
        
        Route::get('/openRevealBearerTokenWindow/{device_id?}',[DeviceController::class, 'openRevealBearerTokenWindow'] )->name('openRevealBearerTokenWindow')->middleware(['password.confirm']);

        Route::post('/device_amend', [DeviceController::class, 'device_amend'])->name("device_amend");
        Route::post('/device_list_action', [DeviceController::class, 'device_list_action'])->name('device_list_action');
        Route::post('/new_device', [DeviceController::class, 'new_device']);
        Route::post('/new_device_two_method', [DeviceController::class, 'new_device_two_method'])->name('new_device_two_method')->withoutMiddleware([VerifyCsrfToken::class]);

        Route::get('/deviceSharedToMe',[DeviceOwnershipShareController::class, 'fetchTable'] )->name('deviceSharedToMe');
        Route::get('/newDeviceWizard', [DeviceController::class, 'newDeviceWizard'])->name('newDeviceWizard');
        Route::post('/save_device_credential', [DeviceController::class, 'save_device_credential'])->name('save_device_credential');

        Route::get('/message', [MessageController::class, 'msg_dashboard_ui'] )->name('message'); //ok
       
        Route::post('/device_share_revoke', [DeviceOwnershipShareController::class, 'device_share_revoke'])->name('device_share_revoke');
        Route::post('/change_right_to/{case_id?}',[DeviceOwnershipShareController::class, 'change_right'] )->name('change_right_to');
        Route::post('/give_up_shared_right', [DeviceOwnershipShareController::class, 'give_up_shared_right'])->name('give_up_shared_right');
        Route::post('/device_share_add/{device_id?}',[DeviceOwnershipShareController::class, 'device_share_add'])->name('device_share_add');
    
       
    });
     //Message Retrieve For Console (All Subs Protected)
     Route::post('/msg_enquiry/{login_session?}', [MessageController::class, 'msg_enquiry'])->name('msg_enquiry_api')->withoutMiddleware([VerifyCsrfToken::class]);
     Route::post('/msg_enquiry_del/{login_session?}/{message_id?}',[MessageController::class, 'msg_delete'] )->name('msg_delete_api')->withoutMiddleware([VerifyCsrfToken::class]);
     Route::post('/msg_enquiry_pin/{login_session?}/{message_id?}/{true_false?}', [MessageController::class, 'msg_pin'] )->name('msg_pin_api')->withoutMiddleware([VerifyCsrfToken::class]);
 
 
    /*Route::get('/billing-portal', function (Request $request) {
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
    Route::get('/subscription', [SubscriptionManagementController::class, 'subscription_dashboard_ui'])->name('subscription_dashboard_ui');
    Route::get('/add_new_setup_intent', [SubscriptionManagementController::class, 'add_new_setup_intent'])->name('add_new_setup_intent');
    Route::post('/delete_payment_method', [SubscriptionManagementController::class, 'delete_payment_method'])->name('delete_payment_method');
    Route::post('/set_default_payment_method', [SubscriptionManagementController::class, 'set_default_payment_method'])->name('set_default_payment_method');
    Route::post('/update_payment_method', [SubscriptionManagementController::class, 'paymentMethodUpdate'])->name('update_payment_method');
    Route::post('/change_plan', [SubscriptionManagementController::class, 'changePlan'])->name('change_plan');
    Route::post('/subscribe_service', [SubscriptionManagementController::class, 'subscribe_service'])->name('subscribe_service');
    Route::post('/cancel_subscription/{SubId?}', [SubscriptionManagementController::class, 'cancelSubscriptionItem'])->name('cancelSubscriptionItem');

    //
    
});



