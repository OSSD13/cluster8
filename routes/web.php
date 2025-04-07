<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login_controller;
use App\Http\Controllers\Home_controller;
use App\Http\Middleware\Check_login;
use App\Http\Controllers\Work_request_controller;
use App\Http\Controllers\Report_controller;
use App\Http\Controllers\Manage_controller;
use App\Http\Controllers\Dashboard_controller;

Route::get('/', function () {
    return view('home');
});

Route::get("/workrequest", [Work_request_controller::class, "index"]);

Route::get("/report", [Report_controller::class, "index"]);

Route::get("/manage", [Manage_controller::class, "index"])->middleware([Check_login::class]);
Route::get('/dashboard',[Dashboard_controller::class,"index"]);
