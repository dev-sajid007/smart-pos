<?php

namespace App\Http\Middleware;

use Closure;

class CheckDebitCreditMiddleware
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
        if($request->type != 'debit' && $request->type != 'credit'){
            abort(404);
        }
        return $next($request);
    }
}
