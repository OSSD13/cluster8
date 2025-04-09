<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    // ดึงค่า task_work_request_id จาก request
    $taskWorkRequestId = $req->task_work_request_id;

    // สมมุติว่า Task ต้องใช้ค่า task_work_request_id
    $task = Task::where('task_work_request_id', $taskWorkRequestId)->first();

    // บันทึก task_work_request_id ลงใน session
    $req->session()->put('task_work_request_id', $taskWorkRequestId);

    // ส่งข้อมูลกลับไปยัง frontend (optional)
    return response()->json(['status' => 'success', 'task' => $task]);
}

}
