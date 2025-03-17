<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('users');
        if ($user == null || $user->user_id == null){
            return redirect('/login');
        }
        elseif ($user['user_ro_id'] != 0) {
            if ($request->path() !== 'home') {
                return redirect('/home');
            }
        } else {
            if ($request->path() !== 'manage') {
                return redirect('/manage');
            }
        }

        return $next($request);
    }
}