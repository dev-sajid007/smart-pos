<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next){
            if (session('success')){
                alert('Success!', session('success'), 'success');
            }
            if (session('error')){
                alert('Something Wrong!', session('error'), 'error');
            }
            if (session('warning')){
                alert('Warning', session('warning'), 'Warning');
            }

//            if(session('errors')){
//                if(session('errors')->any()){
//                    foreach (session('errors')->all() as $key => $error){
//                        toast($error, 'error');
//                    }
//                }
//            }
            return $next($request);
        });
    }
}
