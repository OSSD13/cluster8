<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Manage;

class Manage_controller extends Controller
{
    function index(Request $req)
    {
        $selected_dept = $req->input('department');
        $selected_name = $req->input('user');

        $users = DB::table('users');
        if ($selected_name) {
            $users->where('users.user_fname', 'LIKE', "%{$selected_name}%")
                  ->orWhere('users.user_lname', 'LIKE', "%{$selected_name}%")
                  ->orWhere('users.user_id', 'LIKE', "%{$selected_name}%");
        }
        $users = $users->get();

        $query = DB::table('departments');
        if ($selected_dept) {
            $query->where('department_name', $selected_dept);
        }

        $req = $query->get();
        $departments = DB::table('departments')->select('department_name')->get();

        return view('manage', [
            'req' => $req,
            'select_dept' => $selected_dept,
            'departments' => $departments,
            'search_name' => $selected_name,
            'users' => $users,
        ]);
    }

    function searchUsers(Request $request)
    {
        $search = $request->input('search');

        // ค้นหาชื่อพนักงานที่ตรงกับคำค้นหา หรือดึงข้อมูลทั้งหมดถ้าไม่มีคำค้นหา
        $users = DB::table('users')
                ->select('user_id', DB::raw("CONCAT(user_fname, ' ', user_lname) AS user_name"))
                ->when($search, function ($query, $search) {
                    $query->where(DB::raw("CONCAT(user_fname, ' ', user_lname)"), 'LIKE', "%{$search}%")
                          ->orWhere('user_id', 'LIKE', "%{$search}%");
                })->get();

        return response()->json($users);
    }

    public function edit_dept(Request $request)
    {
        $user_id = $request->input('user_id'); // รับ user_id จากคำขอ
        $department_name = $request->input('department_name'); // รับ department_name จากคำขอ

        // ดึง department_id จากชื่อแผนก
        $department = DB::table('departments')
            ->where('department_name', $department_name)
            ->first();

        if ($department_name == '-') {
                DB::table('users')
                    ->where('user_id', $user_id)
                    ->update(['user_dept_id' => 0]);
                // ลบแผนกของพนักงาน
                return response()->json(['success' => true, 'message' => 'ยกเลิกแผนกสำเร็จ!']);
            }
        if ($department) {
            // อัปเดตแผนกของพนักงาน
            DB::table('users')
                ->where('user_id', $user_id)
                ->update(['user_dept_id' => $department->department_id]);
            return response()->json(['success' => true, 'message' => 'กำหนดแผนกสำเร็จ!']);
        }

        return response()->json(['success' => false, 'message' => 'ไม่พบแผนกที่เลือก']);
    }
}
