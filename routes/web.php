<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CheckLogin;
use App\Http\Controllers\WorkRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ManageController;
use App\Http\Middleware\CheckAdmin;



Route::get("/",[HomeController::class, 'home'])->middleware([CheckLogin::class]);
Route::get("/home",[HomeController::class,'home'])->middleware([CheckLogin::class]);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', function(){
    session()->forget('user');
    session()->flush();
    return redirect('/login');
});

Route::get("/workrequest", [WorkRequestController::class, "index"]);

Route::get("/report", [ReportController::class, "index"]);

Route::get("/manage", [ManageController::class, "index"])->middleware([CheckAdmin::class]);