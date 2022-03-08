<?php
use Illuminate\Support\Facades\Route;



$routeList=[

    [
        'route'=>'/',
        'bladeName'=>'home',
        'routeName'=>'home'
    ]
];

for ($i=0; $i < count($routeList); $i++) { 
    $routeItem=$routeList[$i];
    Route::view($routeItem['route'], 'home.'.$routeItem['bladeName'])->name($routeItem['routeName']);
}

