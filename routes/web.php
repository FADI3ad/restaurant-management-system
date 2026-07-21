<?php

use App\Events\TimelineChange;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');


    Route::resource('sections', SectionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('tables', TableController::class);
    Route::resource('reservations', ReservationController::class);
    Route::get('/cashier', function () {
        return view('cashier');
    })->name('cashier.index');
});

