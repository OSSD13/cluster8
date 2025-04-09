<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard_controller extends Controller
{
    /**
     * แสดงหน้าแดชบอร์ด
     */
     function index()
    {
        // ส่งข้อมูลไปยัง View
        return view('dashboard');
    }
}
