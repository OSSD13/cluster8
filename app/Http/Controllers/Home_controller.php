<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class Home_controller extends Controller
{
    function home()
    {
        return view('home');
    }

    function show_work(Request $req)
    {
        $task = Task::where('task_id', $req->task_id);
        $req->session()->put('task_id', $task->task_id);
        $req->session()->put('task_work_request_id', $task->task_work_request_id);
    }

    
    public function all_task(Request $req)
{
    if ($req->has('task_work_request_id')) {
        $task_work_request_id = $req->input('task_work_request_id');

        // บันทึกข้อมูลใน Session (ถ้าจำเป็นต้องใช้หลายหน้า)
        $req->session()->put('task_work_request_id', $task_work_request_id);

        // ดึงเฉพาะงานที่มี task_work_request_id ตรงกัน
        $data3 = Task::where('task_work_request_id', $task_work_request_id)->get();

        // ส่งไปยัง view พร้อมตัวแปร
        return view('home', compact('data3', 'task_work_request_id'));
    }

    return redirect()->back()->with('error', 'Task work request ID is missing.');
}
}