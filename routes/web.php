<?php

use App\Events\TimelineChange;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/menu', function () {
        return view('menu');
    })->name('menu.index');

    Route::get('/orders', function () {
        return view('orders');
    })->name('orders.index');

    Route::get('/cashier', function () {
        return view('cashier');
    })->name('cashier.index');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::get('/users', function () {
        return view('user');
    })->name('users.index');

    Route::get('/expenses', function () {
        return view('expenses');
    })->name('expenses.index');

    Route::get('/offers', function () {
        return view('offers');
    })->name('offers.index');

    Route::get('/sections', function () {
        return view('section');
    })->name('sections.index');

    Route::get('/categories', function () {
        return view('category');
    })->name('categories.index');

    Route::get('/subcategories', function () {
        return view('subcategory');
    })->name('subcategories.index');

    Route::get('/items', function () {
        return view('item');
    })->name('items.index');

    Route::get('/tables', function () {
        return view('table');
    })->name('tables.index');

    Route::get('/reservations', function () {
        return view('reservation');
    })->name('reservations.index');
});

