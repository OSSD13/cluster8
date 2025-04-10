<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Report_controller extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');  //ดึงค่าที่ผู้ใช้เลือกจากหน้า form ของ report
        $yearBuddhist = $request->input('year'); //เลือกค่าที่เป็น พ.ศ.ที่ผู้ใช้เลือกจากหน้า form ของ report
        $year = is_numeric($yearBuddhist) ? ((int)$yearBuddhist - 543) : null; //แปลงค่าเป็น ค.ศ.เพื่อให้ตรงในฐานข้อมูล เพื่อดึงจากฐาน


        $query = DB::table('work_request_order')
            ->Join('users', 'work_request_order.work_create_by_user_id', '=', 'users.user_id')
            ->Join('departments', 'work_request_order.work_created_by_department_id', '=', 'departments.department_id')
            ->join('task', 'work_request_order.work_request_id', '=', 'task.task_work_request_id')
            ->select(
                'work_request_order.*',
                DB::raw("CONCAT(users.user_fname, ' ', users.user_lname) AS requester_name"),
                //DB::raw(...)	เป็นการเขียน SQL แบบกำหนดเอง  ใช้ในกรณีที่ Laravel ไม่มี function โดยตรงให้ทำ เช่น CONCAT(), IF(), CASE, หรือ SUM(field1 + field2)

                'departments.department_name',
                'task.task_name',
                'task.task_deadline',
                'task.task_recipient_type',
                'task.task_status AS task_status'

                //เลือกสิ่งที่จะใช้ออกมา
            );

        if ($month) {
            $query->whereMonth('work_create_date', $month); //ถ้าผู้ใช้เลือกเดือน ให้แสดงเฉพาะข้อมูลที่ request_date อยู่ในเดือนนั้น
        }

        if ($year) {
            $query->whereYear('work_create_date', $year); //ถ้าผู้ใช้เลือกปี ให้แสดงเฉพาะข้อมูลที่ request_date อยู่ในปีนั้น
        }

        $results = $query->get(); //ดึงข้อมูลทั้งหมดจาก query ที่กรองไว้ แล้วเก็บไว้ในตัวแปร results


        // จัดกลุ่มตาม work_request_id
        $requests = $results->groupBy('work_request_id');

        return view('report', [
            'requests' => $requests,
            'selectedMonth' => $month,
            'selectedYear' => $yearBuddhist

            //ส่งข้อมูลไปยัง View report.blade.php
            // $requests คือข้อมูลรายการ work request
            // $selectedMonth และ $selectedYear ส่งกลับไปให้ view ใช้แสดงผล dropdown แบบเลือกค่าเดิมไว้
        ]);
    }


}
