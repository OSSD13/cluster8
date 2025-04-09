<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home_controller extends Controller
{
    public function home()
{
    // ดึงจำนวน task แยกตามสถานะ
    $waiting = \DB::table('task')->where('task_status', 'waiting')->count();
    $inProgress = \DB::table('task')->where('task_status', 'in_progress')->count();
    $completed = \DB::table('task')->where('task_status', 'completed')->count();

    return view('home', compact('waiting', 'inProgress', 'completed'));
}
public function index()
{
    // ดึงข้อมูลจากฐานข้อมูล
    $waiting = DB::table('tasks')->where('status', 'waiting')->count();
    $inProgress = DB::table('tasks')->where('status', 'in_progress')->count();
    $completed = DB::table('tasks')->where('status', 'completed')->count();

    // ส่งข้อมูลไปยัง View
    return view('home', compact('waiting', 'inProgress', 'completed'));
}

}
