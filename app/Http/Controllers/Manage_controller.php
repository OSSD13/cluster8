<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class Manage_controller extends Controller
{
    //
    function index(){
        $users = User::all();
        $data['users'] = $users;
        return view('manage', ['users'=> $users]);
    }
}
