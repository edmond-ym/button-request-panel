
<?php
use Illuminate\Support\Facades\Route;



//Route::prefix('v1')->group(base_path('routes/api/api_v1.php'));

$routeList=[
    'v1' => 'api_v1.php'
];

foreach ($routeList as $version => $fileName) {
    Route::prefix($version)->group(base_path('routes/api/'.$fileName));
}
   
   