<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class Login_controller extends Controller
{
    
    function index(){
        return view('login');
    }

    function login(Request $req){
        //print_r($req->input());
        $user = User::where('user_username', $req->user_username)->first();
        //print_r($user);
        if($user != null && Hash::check($req->user_password, $user->user_password)){
            $req->session()->put('users', $user);
            return redirect('/');
        }else {
            
            return redirect('/login');
        }
    }
}
