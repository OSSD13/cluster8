<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Task_controller extends Controller
{
    public function returnTask(Request $request)
{
    $taskId = $request->input('task_id');

    // อัปเดตสถานะงาน
    DB::table('task')
        ->where('task_id', $taskId)
        ->update(['task_status' => 'R'])
        ; // เปลี่ยนเป็น 'R' สำหรับงานที่ส่งคืน

    // ดึงข้อมูลล่าสุดของ task ที่มีการอัปเดต
    $task = DB::table('task')
        ->where('task_id', $taskId)
        ->first();

    return response()->json(['success' => true, 'task' => $task]); // ส่งข้อมูลกลับ
}
}
