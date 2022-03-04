
<?php
use Illuminate\Support\Facades\Route;




$routeList=[
    'v1' => 'api_v1.php'
];

foreach ($routeList as $version => $fileName) {
    Route::prefix($version)->group(base_path('routes/api/'.$fileName));
}
   
   

