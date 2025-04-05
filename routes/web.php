<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('home');});

Route::get("/home",[HomeController::class,'home']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);

// Route::get('/',
//     [HomeController::class, 'index'])-> middleware([CheckLogin::class]);


