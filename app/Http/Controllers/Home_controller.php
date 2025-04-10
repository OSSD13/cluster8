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
    /*
    public function accept(Request $req)
    {
        // ดึงข้อมูล work_request_id จากฟอร์ม
        $work_request_id = $req->input('accept_work_id');
    public function home()
    {
        // ดึงจำนวน task แยกตามสถานะ
        $waiting = DB::table('task')->where('task_status', 'waiting')->count();
        $inProgress = DB::table('task')->where('task_status', 'in_progress')->count();
        $completed = DB::table('task')->where('task_status', 'completed')->count();
    
        // ส่งข้อมูลไปยัง View
        return view('home', compact('waiting', 'inProgress', 'completed'));
    }
    

        // ค้นหา Work_Request_Order ที่ต้องการลบ
        $mwrq = \App\Models\Work_Request_Order::find($work_request_id);
        $mtask = new \App\Models\Task;
        if ($mwrq) {
            // ลบ Work_Request_Order
            $mtask->work_status = 'P';
            $mtask->task_recipient_user_id = session('users')->user_id;
            $mtask->save();
            
        }
        // Redirect กลับไปที่หน้า workrequest
        return redirect('/workrequest');
    }
        */
        
}
