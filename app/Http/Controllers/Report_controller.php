<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Report_controller extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');  //ดึงค่าที่ผู้ใช้เลือกจากหน้าreport
        $yearBuddhist = $request->input('year'); //เลือกค่าที่เป็น พ.ศ.ที่ผู้ใช้เลือกจากหน้า report
        $year = is_numeric($yearBuddhist) ? ((int)$yearBuddhist - 543) : null; //แปลงค่าเป็น ค.ศ.เพื่อให้ตรงในฐานข้อมูล

        $query = DB::table('work_request_order');  //สร้าง query ที่จะดึงข้อมูลจากตาราง work_request_order

        if ($month) {
            $query->whereMonth('work_create_date', $month); //ถ้าผู้ใช้เลือกเดือน ให้แสดงเฉพาะข้อมูลที่ request_date อยู่ในเดือนนั้น
        }

        if ($year) {
            $query->whereYear('work_create_date', $year); //ถ้าผู้ใช้เลือกปี ให้แสดงเฉพาะข้อมูลที่ request_date อยู่ในปีนั้น
        }

        $requests = $query->get(); //ดึงข้อมูลทั้งหมดจาก query ที่กรองไว้ แล้วเก็บไว้ในตัวแปร $requests

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
