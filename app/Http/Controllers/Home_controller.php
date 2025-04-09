<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Home_controller extends Controller
{
    function home()
    {
        return view('home');
    }

    public function acceptTask(Request $request)
{
    $taskId = $request->input('task_id');
    $userId = session('users')->user_id;

    
    $updated = DB::table('task')
        ->where('task_id', $taskId)
        ->update([
            'task_status' => 'P',
            'task_recipient_user_id' => $userId
        ]);
}
}
