<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_request_order;
use App\Models\Task;
use App\Models\User;

class Work_request_controller extends Controller
{
    //
    function index(){
        $work_request_order = Work_request_order::all();
        $task_list = Task::all();
        $user = User::all();
        $data['work_request_order'] = $work_request_order;
        $data['task'] = $task_list;
        $data['user'] = $user;
        return view('work_request', $data);
    }



    function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
        ]);

        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'owner_name' => $request->owner_name,
        ]);

        return redirect()->route('projects.index')->with('success', 'บันทึกโครงงานเรียบร้อยแล้ว');
    }
}
