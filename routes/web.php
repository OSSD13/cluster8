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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Task_controller;




Route::get('/login', [Login_controller::class, 'index']);
Route::post('/login', [Login_controller::class, 'login']);
Route::get('/logout', function(){
    session()->forget('user');
    session()->flush();
    return redirect('/login');
});


Route::get("/",[Home_controller::class, 'home'])->middleware([Check_login::class]);
Route::get("/home",[Home_controller::class,'home'])->middleware([Check_login::class]);
Route::post("/home",[Home_controller::class,'decline'])->middleware([Check_login::class]);

Route::post('/return-task', [Task_controller::class, 'returnTask'])->name('task.return');


Route::get("/workrequest", [Work_request_controller::class, "index"]);
Route::post("/workrequest/create", [Work_request_controller::class, "create"])->name('workrequest.create');
Route::get("/workrequest", [Work_request_controller::class, "show"]);
Route::post("/workrequest", [Work_request_controller::class, "store"]);

Route::get("/report", [Report_controller::class, "index"])->name('report');

Route::get("/manage", [Manage_controller::class, "index"])->middleware([Check_login::class]);
Route::get('/manage/search-users', [Manage_controller::class, 'searchUsers']);
Route::post('/manage/search-users', [Manage_controller::class, 'searchUsers'])->name('manage.searchUsers');

Route::post('/manage/edit-dept', [Manage_controller::class, 'edit_dept'])->name('edit.dept');

Route::post('/manage/filter-by-department', [Manage_controller::class, 'filterByDepartment']);

Route::get('/dashboard',[Dashboard_controller::class,"index"]);
Route::post('/update-userclick', function (Request $request) {
    $Userclick = $request->input('Userclick');
    session(['Userclick' => $Userclick]);

    $userID = session('users')->user_id;

    // ดึงข้อมูลสำหรับส่วนตัว
    $completedTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'P')->where('task_status', 'C')->count();
    $pendingTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'P')->where('task_status', 'P')->count();

    // ดึงข้อมูลสำหรับแผนก
    $completedDepartmentTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'D')->where('task_status', 'C')->count();
    $pendingDepartmentTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'D')->where('task_status', 'P')->count();

    // ดึงข้อมูลสำหรับปฏิเสธงาน
    $decrydingTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'P')->where('task_status', 'R')->count();
    $decrydingDepartmentTasks = DB::table('task')->where('task_recipient_user_id', $userID)->where('task_recipient_type', 'D')->where('task_status', 'R')->count();

    return response()->json([
        'success' => true,
        'Userclick' => $Userclick,
        'completedTasks' => $completedTasks,
        'completedDepartmentTasks' => $completedDepartmentTasks,
        'pendingTasks' => $pendingTasks,
        'pendingDepartmentTasks' => $pendingDepartmentTasks,
        'decrydingTasks' => $decrydingTasks,
        'decrydingDepartmentTasks' => $decrydingDepartmentTasks,
    ]);
});



