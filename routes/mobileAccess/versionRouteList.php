<?php
use Illuminate\Support\Facades\Route;



$routeList=[
     'v1' => 'mobileAccess_v1.php'
];

foreach ($routeList as $version => $fileName) {
     Route::prefix($version)->group(base_path('routes/mobileAccess/'.$fileName));
}

