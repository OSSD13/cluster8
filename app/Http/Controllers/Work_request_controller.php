<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_req;
use App\Models\Sub_req;
use App\Models\User;
class Work_request_controller extends Controller
{
    //
    function index(){
        $works_requests = Work_req::all();
        $subs_works_list = Sub_req::all();
        $user = User::all();
        $data['works_requests'] = $works_requests;
        $data['subs_works'] = $subs_works_list;
        $data['user'] = $user;
        return view('work_request', $data);
    }

    function add_works_requests(Request $req){
        $works_requests = new Work_req();
        $works_requests->name = $req->works_requests_name;
        $works_requests->save();

        foreach($req->subs_works_list_name as $value){
            $subs_works = new subs_works();
            $subs_works->name = $value;
            $subs_works->works_requests_id = $works_requests->id;
            $subs_works->user_id = session('user')->id;
            $subs_works->save();
        }

        return redirect('/workrequest');
    }
}
