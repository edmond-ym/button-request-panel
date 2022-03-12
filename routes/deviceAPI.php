
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceAPIController;



//device api
//To launch request, Query Param button_id need to be given and Bearer Token need to be given. Link: deviceAPI/{device_id}
/*Route::get('*', function(){ 

    return ['result'=>"GET Method is Not Supported in API"];
});*/

Route::post('/v1/{device_id?}', [DeviceAPIController::class, 'push_msg'])->withoutMiddleware([VerifyCsrfToken::class])->name("deviceAPI.v1");//Subs Protected

