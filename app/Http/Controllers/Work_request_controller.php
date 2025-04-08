<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Work_Request_Order;
use App\Models\Task;
use App\Models\User;

class Work_request_controller extends Controller
{
    //
    function index(){
        return view('work_request');
    }
    function show(){
        $users = User::all();
        $data['users'] = $users;
        return view('work_request', ['users' => $users]);
    }

    function create(Request $req) {
        
        $status = $req->input('work_status');
    
        // validate ตาม action
        if ($status === 'R') {
            // ถ้ากดปุ่ม "ส่ง"
            $req->validate([
                'work_name' => 'required|string|max:255',
                'work_author_type' => 'required',
                'task_name' => 'required|array|min:1',
                'task_name.*' => 'required|string|max:255',
                'task_deadline' => 'required|array',
                'task_deadline.*' => 'required|date',
                'task_recipient_type' => 'required|array',
                'task_recipient_type.*' => 'required',
            ]);
        } elseif ($status === 'draft') {
            // ถ้ากดปุ่ม "แบบร่าง"
            $req->validate([
                'work_name' => 'required|string|max:255',
            ]);
        }
    
        // ========== บันทึก Work Request ==========
        $mwrq = new \App\Models\Work_Request_Order;
        $mwrq->work_name = $req->input('work_name');
        $mwrq->work_create_date = now()->toDateString();
        $mwrq->work_submit_date = $status === 'R' ? now()->toDateString() : null;
        $mwrq->work_create_by_user_id = session('users')->user_id;
        $mwrq->work_author_type = $req->input('work_author_type');
        $mwrq->work_status = $status;
        $mwrq->work_created_by_department_id = session('users')->user_dept_id;
        $mwrq->work_confirm_date = null;
        $mwrq->save();
        $workRequestId = $mwrq->work_request_id;
    
        // ========== บันทึก Tasks ถ้ามี ==========
        if ($status === 'R') {
            $taskNames = $req->input('task_name', []);
            $taskDeadlines = $req->input('task_deadline', []);
            $taskRecipientTypes = $req->input('task_recipient_type', []);
            $taskRecipientUserIds = $req->input('task_recipient_user_id', []);
            $taskRecipientDepartmentIds = $req->input('task_recipient_department_id', []);
    
            foreach ($taskNames as $i => $name) {
                $mtask = new Task();
                $mtask->task_work_request_id = $workRequestId;
                $mtask->task_name = $name;
                $mtask->task_deadline = $taskDeadlines[$i] ?? null;
                $mtask->task_status = $status;
                $mtask->task_recipient_type = $taskRecipientTypes[$i] ?? null;
                $mtask->task_recipient_user_id = ($taskRecipientUserIds[$i] ?? '-') !== '-' ? $taskRecipientUserIds[$i] : null;
                $mtask->task_recipient_department_id = ($taskRecipientDepartmentIds[$i] ?? '-') !== '-' ? $taskRecipientDepartmentIds[$i] : null;
                $mtask->task_notation = null;
                $mtask->task_submit_date = null;
                $mtask->save();
            }
        }
    
        return redirect('/workrequest');
    }
    




    public function store(Request $req)
    {
        // ดึงข้อมูล work_request_id จากฟอร์ม
        $work_request_id = $req->input('confirm_work_id');

        // ค้นหา Work_Request_Order ที่ต้องการอัปเดต
        $mwrq = \App\Models\Work_Request_Order::find($work_request_id);

        if ($mwrq) {
            // อัปเดต work_confirm_date เป็นวันที่ปัจจุบัน
            $mwrq->work_confirm_date = now();
            $mwrq->save();
        }

        // Redirect กลับไปที่หน้า workrequest
        return redirect('/workrequest');
    }



    
}