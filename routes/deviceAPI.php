
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;



//device api
//To launch request, Query Param button_id need to be given and Bearer Token need to be given. Link: deviceAPI/{device_id}


Route::post('/v1/{device_id?}', [DeviceAPIController::class, 'push_msg'])->withoutMiddleware([VerifyCsrfToken::class]);//Subs Protected

