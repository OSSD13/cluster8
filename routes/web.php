<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CheckLogin;



Route::get("/",[HomeController::class, 'home'])->middleware([CheckLogin::class]);
Route::get("/home",[HomeController::class,'home'])->middleware([CheckLogin::class]);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', function(){
    session()->forget('user');
    session()->flush();
    return redirect('/login');
});

