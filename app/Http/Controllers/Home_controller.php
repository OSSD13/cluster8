<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;


class Home_controller extends Controller
{
    function home()
    {
        // ดึงจำนวน task แยกตามสถานะ
        $waiting = DB::table('task')->where('task_status', 'waiting')->count();
        $inProgress = DB::table('task')->where('task_status', 'in_progress')->count();
        $completed = DB::table('task')->where('task_status', 'completed')->count();
    
        // ส่งข้อมูลไปยัง View
        return view('home', compact('waiting', 'inProgress', 'completed'));
    }
    
    public function decline(Request $req) 
    {
    // ดึงข้อมูล work_request_id จากฟอร์ม
    $decline_work_id = $req->input('decline_work_id');

    // ค้นหา Work_Request_Order ที่ต้องการปฏิเสธ
    $mwrq = \App\Models\Work_Request_Order::find($decline_work_id);
    //dd($req->all());

    if ($mwrq) {
        // อัปเดตข้อมูลในฐานข้อมูล
        $mwrq->work_decline = $req->input('work_decline'); // ข้อความปฏิเสธ
        $mwrq->work_status = 'D'; // เปลี่ยนสถานะเป็น 'D' (ปฏิเสธ)
        $mwrq->work_submit_date = now(); // บันทึกวันที่ปัจจุบัน
        $mwrq->save();

        
    }

        
        return redirect('/home');
    }
    

    public function acceptWork(Request $request)
{
    $taskId = $request->input('task_id');

    $updated = DB::table('task')
        ->where('task_id', $taskId)
        ->update([
            'task_status' => 'P', // เปลี่ยนสถานะเป็น "กำลังดำเนินการ"
        ]);

    if ($updated) {
        return response()->json(['success' => true, 'message' => 'รับงานสำเร็จ']);
    } else {
        return response()->json(['success' => false, 'message' => 'ไม่สามารถรับงานได้']);
    }
}

    public function submit_task(Request $request)
    {
    $taskId = $request->input('task_id');
    $notation = $request->input('notation');

    $updated = DB::table('task')
        ->where('task_id', $taskId)
        ->update([
            'task_status' => 'C', // เปลี่ยนสถานะเป็น "เสร็จสิ้น"
            'task_submit_date' => now(), // บันทึกวันที่ส่งงาน
            'task_notation' => $notation // บันทึกหมายเหตุ
        ]);

    if ($updated) {
        return response()->json(['success' => true, 'message' => 'ส่งงานสำเร็จ']);
    } else {
        return response()->json(['success' => false, 'message' => 'ไม่สามารถส่งงานได้']);
    }
    }
}
