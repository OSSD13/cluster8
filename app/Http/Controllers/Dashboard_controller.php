<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Dashboard_controller extends Controller
{
    /**
     * แสดงหน้าแดชบอร์ดพร้อมข้อมูลสถานะงาน
     */
    public function index(Request $request)
    {
        return view('dashboard');
    }
}