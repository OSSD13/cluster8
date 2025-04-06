<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Work_Request_Order;
use App\Models\Task;

class Work_request_controller extends Controller
{
    //
    function index(){
       
        return view('work_request');
    }

    function create(Request $req){
        print_r($req->input());
        $mwrq = new Work_Request_Order();
        $mtask = new Task();

        $mwrq->work_name = $req->input('work_name');
        $mwrq->work_create_date = $req->input('create_date');
        $mwrq->work_create_by_user_id = $req->input('');
        $mwrq->work_author_type = $req->input('work_author_type');
        $mwrq->work_status = $req->input('R');
        $mwrq->work_create_by_department_id = $req->input('');
        $mwrq->save();
        $mtask->task_work_request_id = $mwrq->work_request_id;
        $mtask->task_name = $req->input('task_name');
        $mtask->task_deadline = $req->input('task_deadline');
        $mtask->task_recipient_user_id = $req->input('task_recipient_user_id');
        $mtask->task_recipient_department_id = $req->input('task_recipient_department_id');
        $mtask->task_recipient_type = $req->input('task_recipient_type_');
        $mtask->save();
    
        return redirect('/work_request')->with('status', 'Work request created successfully');
    }
}
