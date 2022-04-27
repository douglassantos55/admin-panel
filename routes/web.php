<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

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

Route::controller(CustomerController::class)->group(function () {
    Route::get('/customers', 'index')->name('customers.index');
    Route::get('/customers/create', 'create')->name('customers.create');
    Route::post('/customers/store', 'store')->name('customers.store');
    Route::delete('/customers/{customer}', 'destroy')->name('customers.destroy');
});

Route::get('/', function () {
    return inertia('Welcome');
});
