<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class Home_controller extends Controller
{



    public function home()
{
    

    return view('home');
}
}
