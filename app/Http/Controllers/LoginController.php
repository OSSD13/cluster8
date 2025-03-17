<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class LoginController extends Controller
{
    
    function index(){
        return view('login');
    }

    function login(Request $req){
        //print_r($req->input());
        $user = User::where('username', $req->username)->first();
        //print_r($user);
        if($user != null && Hash::check($req->password, $user->password)){
            $req->session()->put('users', $user);
            return redirect('/');
        }else {
            
            return redirect('/login');
        }
    }
}
