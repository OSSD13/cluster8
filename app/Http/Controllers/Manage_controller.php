<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Manage_controller extends Controller
{
    function index(){
        return view('manage');
    }
    function manage(Request $req){
        $search = $req->get('query');
        // $search = $req->input('user_username');

        if ($search){
        // $user = User::where('user_username', $search)->first();
            $users = DB::table('users')
                ->where('user_fname', 'like', '%' . $search . '%')
                ->orWhere('user_lname', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%')
                    ->get(['user_fname', 'user_lname', 'user_id']);

            return response()->json($users); //ส่งข้อมูลไปยังจอแบบไม่โหลดหน้า
        } else {
            return response()->json([]); //ไม่แสดง
        }
    }
}
