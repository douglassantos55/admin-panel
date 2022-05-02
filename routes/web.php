<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/reset-password', 'resetPassword')->name('reset.password');
});

Route::middleware('auth')->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers', 'index')->name('customers.index');
        Route::get('/customers/create', 'create')->name('customers.create');
        Route::post('/customers/store', 'store')->name('customers.store');
        Route::delete('/customers/{customer}', 'destroy')->name('customers.destroy');
        Route::get('/customers/edit/{customer}', 'edit')->name('customers.edit');
        Route::put('/customers/update/{customer}', 'update')->name('customers.update');
    });

    Route::controller(SupplierController::class)->group(function () {
        Route::get('/suppliers', 'index')->name('suppliers.index');
        Route::get('/suppliers/create', 'create')->name('suppliers.create');
        Route::post('/suppliers/store', 'store')->name('suppliers.store');
        Route::get('/suppliers/edit/{supplier}', 'edit')->name('suppliers.edit');
        Route::put('/suppliers/update/{supplier}', 'update')->name('suppliers.update');
        Route::delete('/suppliers/destroy/{supplier}', 'destroy')->name('suppliers.destroy');
    });

    Route::controller(PeriodController::class)->group(function () {
        Route::get('/periods/create', 'create')->name('periods.create');
        Route::post('/periods/store', 'store')->name('periods.store');
        Route::get('/periods', 'index')->name('periods.index');
    });

    Route::get('/', function () {
        return inertia('Welcome');
    })->name('dashboard');
});
