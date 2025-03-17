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
        }else {
            if ($user->user_ro_id != 0){
                return $next($request);
            }
            return $next('/manage');
        }
        
    }
}
