<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');


    Route::resource('sections', SectionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('tables', \App\Http\Controllers\TableController::class);

});
