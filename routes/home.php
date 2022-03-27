<?php
use Illuminate\Support\Facades\Route;



$routeList=[

    [
        'route'=>'/',
        'bladeName'=>'home',
        'routeName'=>'home.home'
    ],
    [
        'route'=>'/messageApp',
        'bladeName'=>'messageApp',
        'routeName'=>'home.messageApp'
    ],
    [
        'route'=>'/features',
        'bladeName'=>'features',
        'routeName'=>'home.features'
    ]
];

for ($i=0; $i < count($routeList); $i++) { 
    $routeItem=$routeList[$i];
    Route::view($routeItem['route'], 'home.'.$routeItem['bladeName'])->name($routeItem['routeName']);
}

Route::get('/api-doc/{version?}', function ($version=null) {
    
    $latestVersion="v1";
    $versionYamlLink=[
        'v1'=>asset('apiDoc/definitions/v1/openapi.yaml'),
    ];
    $YamlUrl=$versionYamlLink[$latestVersion];
    if ($version=="v1" ) {
        $YamlUrl=$versionYamlLink[$version];
    }
    
    return view('home.apiDocumentation', ["url"=>$YamlUrl]);

})->name('apiDoc');        

Route::get('/device-api-doc/{version?}', function ($version=null) {
    
    $latestVersion="v1";
    $versionYamlLink=[
        'v1'=>asset('deviceAPIDoc/v1_0.yaml')
    ];
    $YamlUrl=$versionYamlLink[$latestVersion];
    if ($version=="v1" ) {
        $YamlUrl=$versionYamlLink[$version];
    }
    
    return view('home.deviceApiDocumentation', ["url"=>$YamlUrl]);

})->name('deviceApiDoc'); 