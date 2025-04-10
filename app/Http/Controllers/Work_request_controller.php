<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    function create(Request $req){
        print_r($req->input());
        $mwrq = new \App\Models\Work_Request_Order;
        $mtask = new \App\Models\Task;

        $mwrq->work_name = $req->input('work_name');
        $mwrq->work_create_date = now()->toDateString();
        $mwrq->work_submit_date = null;
        $mwrq->work_create_by_user_id = session('users')->user_id;
        $mwrq->work_author_type = $req->input('work_author_type');
        $mwrq->work_status = $req->input('work_status');
        $mwrq->work_created_by_department_id = session('users')->user_dept_id;
        $mwrq->work_confirm_date = null;
        $mwrq->save();
        $workRequestId = $mwrq->work_request_id;


        $taskNames = $req->input('task_name', []);
        $taskDeadlines = $req->input('task_deadline', []);
        $taskRecipientTypes = $req->input('task_recipient_type', []);
        $taskRecipientUserIds = $req->input('task_recipient_user_id', []);
        $taskRecipientDepartmentIds = $req->input('task_recipient_department_id', []);

        for ($i = 0; $i < count($taskNames); $i++) {
        $mtask = new Task();
        $mtask->task_work_request_id = $workRequestId;
        $mtask->task_name = $taskNames[$i];
        $mtask->task_deadline = $taskDeadlines[$i];
        $mtask->task_status = 'R';
        $mtask->task_recipient_type = $taskRecipientTypes[$i];
        $mtask->task_recipient_user_id = $taskRecipientUserIds[$i] !== '-' ? $taskRecipientUserIds[$i] : null;
        $mtask->task_recipient_department_id = $taskRecipientDepartmentIds[$i] !== '-' ? $taskRecipientDepartmentIds[$i] : null;
        $mtask->task_notation = null;
        $mtask->task_submit_date = null;
        $mtask->save();
    }



        return redirect('/workrequest');
    }




}
