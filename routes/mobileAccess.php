<?php


use App\Http\Controllers\MobileAPIController;



Route::post('/fetchMessage/{access_token?}/{phone_token?}', [MobileAPIController::class, 'fetch_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected 
Route::post('/DeleteMessage/{access_token?}/{phone_token?}/{msg_id?}', [MobileAPIController::class, 'delete_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/PinMessage/{access_token?}/{phone_token?}/{msg_id?}/{true_false?}', [MobileAPIController::class, 'pin_message'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/ValidCheck/{access_token?}/{phone_token?}', [MobileAPIController::class, 'AccessTokenValidCheck'])->withoutMiddleware([VerifyCsrfToken::class]);//protected
Route::post('/mobileConnect/{access_token?}/{randStrFromPhone?}', [MobileAPIController::class, 'mobileConnect'])->withoutMiddleware([VerifyCsrfToken::class]);//no need
Route::post('/mobileBasicData/{access_token?}/{phone_token?}', [MobileAPIController::class, 'basic_data'])->withoutMiddleware([VerifyCsrfToken::class]);//no need
Route::post('/mobileDisconnect/{access_token?}', [MobileAPIController::class, 'mobileDisconnect'])->withoutMiddleware([VerifyCsrfToken::class]);//no need


Route::post('/DeviceList/{access_token?}/{phone_token?}/{device_id?}', [MobileAPIController::class, 'fetch_device_list'])->withoutMiddleware([VerifyCsrfToken::class]);//protected


