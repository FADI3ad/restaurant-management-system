<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     Log::info('User accessed the home page');
//     return view('home');

// })->name('home');


Route::get('/', function(){
    return view('home');
});