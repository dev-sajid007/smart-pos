<?php

namespace App\Http\Middleware;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // set condition, if fill up condition then continue target request
        if (checkIsPermitted())  {
            return $next($request);
        } else {
            // if false condition then redirect to login or another where you want to send
            return redirect('/home');
        }
    }

}
