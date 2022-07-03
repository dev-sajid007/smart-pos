<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\UserRole;

class CheckSystemAdminMiddleware
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
        $role_id = UserRole::where('user_id', Auth::user()->id)->firstOrFail()->role_id;
        
        if($role_id != 1){
            abort('403');
        }

        return $next($request);
    }
}
