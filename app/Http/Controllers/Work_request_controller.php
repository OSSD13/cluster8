<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Work_request_controller extends Controller
{
    //
    function index(){
        return view('work_request');
    }

    function create(Request $req){
        print_r($req->input());
        $mwrq = new \App\Models\Work_Request_Order;
        $mtask = new \App\Models\Task;

        $mwrq->work_name = $req->input('');
        $mwrq->work_create_date = $req->input('');
        $mwrq->work_submit_date = $req->input('');
        $mwrq->work_create_by_user_id = $req->input('');
        $mwrq->work_author_type = $req->input('');
        $mwrq->work_sub_task_id = $req->input('');
        $mwrq->work_status = $req->input('R');
        $mwrq->work_create_by_department_id = $req->input('');
        $mwrq->save();
        $mtask->task_name = $req->input('');
        $mtask->task_deadline = $req->input('');
        $mtask->task_status = $req->input('');
        $mtask->task_recipient_user_id = $req->input('');
        $mtask->task_recipient_department_id = $req->input('');
        $mtask->task_notation = $req->input('');
        $mtask->task_recipient_type = $req->input('');
        $mwrq->task_submit_date = $req->input('');
        $mtask->save();

        return redirect('/work_request')->with('status', 'Work request created successfully');
    }
}
