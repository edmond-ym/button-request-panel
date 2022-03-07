
<?php
use Illuminate\Support\Facades\Route;



/*Route::get('/*', function(){ 
    return ['result'=>"GET Method is Not Supported in API"];
});*/

$routeList=[
    'v1' => 'api_v1.php'
];

foreach ($routeList as $version => $fileName) {
    Route::prefix($version)->group(base_path('routes/api/'.$fileName));
}
   
   

