<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Login_controller;
use App\Http\Controllers\Home_controller;
use App\Http\Middleware\Check_login;
use App\Http\Controllers\Work_request_controller;
use App\Http\Controllers\Report_controller;
use App\Http\Controllers\Manage_controller;

Route::get("/",[Home_controller::class, 'home'])->middleware([Check_login::class]);
Route::get("/home",[Home_controller::class,'home'])->middleware([Check_login::class]);

Route::get('/login', [Login_controller::class, 'index']);
Route::post('/login', [Login_controller::class, 'login']);
Route::get('/logout', function(){
    session()->forget('user');
    session()->flush();
    return redirect('/login');
});

Route::get("/workrequest", [Work_request_controller::class, "index"]);

Route::get("/report", [Report_controller::class, "index"]);

Route::get("/manage", [Manage_controller::class, "index"])->middleware([Check_login::class]);
Route::get('/manage/search-users', [Manage_controller::class, 'searchUsers']);
Route::post('/manage/search-users', [Manage_controller::class, 'searchUsers'])->name('manage.searchUsers');

Route::post('/manage/edit-dept', [Manage_controller::class, 'edit_dept'])->name('edit.dept');

Route::post('/manage/filter-by-department', [Manage_controller::class, 'filterByDepartment']);
