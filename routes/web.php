<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;

// Authentication routes
Auth::routes();

// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Wave routes
Wave::routes();

// Socialite auth
Route::middleware('guest')->group(function () {
    Route::get('auth/google/redirect', [App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])->name('google.login');
    Route::get('auth/google/callback', [App\Http\Controllers\Auth\SocialiteController::class, 'callback']);
});

// Xendit
Route::middleware('auth')->group(function () {
    Route::get('payment/{id}', '\App\Http\Controllers\Xendit\PaymentController@payment')->name('wave.payment');
	Route::get('/success', [\App\Http\Controllers\Xendit\AfterCartController::class, 'success'])->name('success');
	Route::get('/failure', [\App\Http\Controllers\Xendit\AfterCartController::class, 'failure'])->name('failure');
});
Route::post('/xendit/callback', \App\Http\Controllers\Xendit\CallbackInvoiceController::class);