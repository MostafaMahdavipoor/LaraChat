<?php

use App\Http\Controllers\Messenger\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;

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

Route::get('/', function () {
    return view('Messenger.login');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});
Route::prefix('messages')->group(function () {
    Route::get('/set', [MessageController::class, 'set']);
    Route::get('/get', [MessageController::class, 'get']);
    Route::post('/uploadFile', [MessageController::class, 'uploadFile']);
    Route::get('/getContact', [MessageController::class, 'getContact']);
    Route::get('/SetContact', [MessageController::class, 'SetContact']);
});

