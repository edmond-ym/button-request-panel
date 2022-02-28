<?php
use Illuminate\Support\Facades\Route;


//echo __DIR__.'/mobileAccess_v1.php';


$routeList=[
     'v1' => 'mobileAccess_v1.php'
];

foreach ($routeList as $version => $fileName) {
     //Route::prefix('v1')->group(base_path('routes/mobileAccess/mobileAccess_v1.php'));
     Route::prefix($version)->group(base_path('routes/mobileAccess/'.$fileName));
}

