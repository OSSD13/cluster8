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
    
    public function decline(Request $req) 
    {
        // ดึงข้อมูล task_id จากฟอร์ม
        $decline_work_id = $req->input('decline_work_id');

        // ค้นหา Work_Request_Order ที่ต้องการอ
        $mwrq = \App\Models\Work_Request_Order::find($decline_work_id);
        // ค้นหา Task ที่ต้องการปฏิเสธ
        //dd($mwrq);
        if ($mwrq) {
            // อัปเดต work_decline_date เป็นวันที่ปัจจุบัน
            
            $mwrq->work_submit_date = now();
            $mwrq->work_status = 'D';
            $mwrq->work_decline = $req->input('work_decline');
            $mwrq->save();
        }

        
        return redirect('/home');
    }
    /*
    public function accept(Request $req)
    {
        // ดึงข้อมูล work_request_id จากฟอร์ม
        $work_request_id = $req->input('accept_work_id');

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