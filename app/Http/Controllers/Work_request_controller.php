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
        $mwrq = new Work_Request_Order();
        $mtask = new Task();

        $mwrq->work_name = $req->input('work_name');
        $mwrq->work_create_date = now()->toDateString();
        $mwrq->work_submit_date = '0000-00-00';
        $mwrq->work_create_by_user_id = session('users')->user_id;
        $mwrq->work_author_type = $req->input('work_author_type');
        $mwrq->work_status = $req->input('work_status');
        $mwrq->work_create_by_department_id = session('users')->user_dept_id;
        $mwrq->work_confirm_date = '0000-00-00';
        $mwrq->save();

        /*
        foreach () {
            $mtask->task_work_request_id = $mwrq->work_request_id;
            $mtask->task_name = $req->input('task_name')[$index] ?? null; 
            $mtask->task_status = $req->input('task_status')[$index] ?? 'R'; 
            $mtask->task_deadline = $req->input('task_deadline')[$index] ?? null; 
            $mtask->task_recipient_user_id = $req->input('task_recipient_user_id')[$index] ?? null; 
            $mtask->task_recipient_department_id = $req->input('task_recipient_department_id')[$index] ?? null; 
            $mtask->task_recipient_type = $req->input('task_recipient_type')[$index] ?? null; 
            $mtask->task_notation = $req->input('task_notation')[$index] ?? '-'; 
            $mtask->task_submit_date = $req->input('task_submit_date')[$index] ?? '0000-00-00'; 
            $mtask->save();
        }
        */
    
        return redirect('/workrequest');
    }
}
