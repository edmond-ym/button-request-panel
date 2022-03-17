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

