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
        $user = User::where('user_username', $req->user_username)->first();
    
        if($user != null && Hash::check($req->user_password, $user->user_password)){
            $req->session()->put('users', $user);
            $req->session()->put('user_id', $user->user_id); // เก็บ user_id แยกไว้ใน session ด้วย
    
            // ใช้ dd() เพื่อ debug ดูค่า user_id
            // dd($user->user_id);
    
            return redirect('/');
        } else {
            return redirect('/login');
        }
    }
}