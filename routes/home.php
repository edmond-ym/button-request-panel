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
    ],
    [
        'route'=>'/api-doc',
        'bladeName'=>'apiDocumentation',
        'routeName'=>'apiDoc'
    ],
    [
        'route'=>'/device-api-doc',
        'bladeName'=>'deviceApiDocumentation',
        'routeName'=>'deviceApiDoc'
    ]
];

for ($i=0; $i < count($routeList); $i++) { 
    $routeItem=$routeList[$i];
    Route::view($routeItem['route'], 'home.'.$routeItem['bladeName'])->name($routeItem['routeName']);
}

