<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Login_controller;
use App\Http\Controllers\Home_controller;
use App\Http\Middleware\Check_login;
use App\Http\Controllers\Work_request_controller;
use App\Http\Controllers\Report_controller;
use App\Http\Controllers\Manage_controller;
use App\Http\Controllers\Dashboard_controller;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get("/",[Home_controller::class, 'home'])->middleware([Check_login::class]);
Route::get("/home",[Home_controller::class,'home'])->middleware([Check_login::class]);

Route::get('/login', [Login_controller::class, 'index']);
Route::post('/login', [Login_controller::class, 'login']);
Route::get('/logout', function(){
    session()->forget('user');
    session()->flush();
    return redirect('/login');
});


Route::get("/",[Home_controller::class, 'home'])->middleware([Check_login::class]);
Route::get("/home",[Home_controller::class,'home'])->middleware([Check_login::class]);
Route::post('/accept_task', [Home_controller::class, 'acceptWork'])->name('accept.task');
Route::post('/retrun_task', [Home_controller::class, 'return_task'])->name('return.task');

Route::get("/workrequest", [Work_request_controller::class, "index"]);
Route::post("/workrequest", [Work_request_controller::class, "create"]);
Route::get("/workrequest", [Work_request_controller::class, "show"]);

Route::get("/report", [Report_controller::class, "index"])->name('report');

Route::get("/manage", [Manage_controller::class, "index"])->middleware([Check_login::class]);
Route::get('/manage/search-users', [Manage_controller::class, 'searchUsers']);

Route::post('/manage/edit-dept', [Manage_controller::class, 'edit_dept'])->name('edit.dept');

Route::get("/dashboard",[Dashboard_controller::class, 'index']);
