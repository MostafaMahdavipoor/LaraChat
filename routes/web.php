<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerController;


Route::get('/', function () {
    return view('chat');
});

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/messages/set', [MessengerController::class, 'store']);
    Route::get('/messages/update', [MessengerController::class, 'update']);
    Route::get('/messages/delete', [MessengerController::class, 'destroy']);
    Route::get('/message/list', [MessengerController::class, 'get']);
    Route::post('/upload-file', [MessengerController::class, 'uploadFile'])->name('upload-file');
});
