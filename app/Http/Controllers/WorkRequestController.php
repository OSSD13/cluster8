<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkRequestController extends Controller
{
    //
    function index(){
        return view('workrequest');
    }
}
