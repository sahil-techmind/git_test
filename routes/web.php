<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/login', [Controller::class, 'index']);



Route::get('/google/login', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/google/callback', [GoogleController::class, 'callback']);
