<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\UserRole;
use App\Permission;
use App\RolePermission;
use Auth;
use App\Company;
use Carbon\Carbon;
use App\CompanyPackage;

class CheckUserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $check_permission)
    {
        $role_id = UserRole::where([
            'user_id' => Auth::user()->id
        ])->first()->role_id;

        $company_id = Auth::user()->fk_company_id;

        if($company_id != 1){
            if(strpos($check_permission, 'view') == false){
                $today = Carbon::today()->toDateString();
                $company_package = CompanyPackage::where('fk_company_id', $company_id)
                ->where('trial_ends_at','>=',$today)->orWhere('ends_at','>=',$today)
                ->get()->count();
            }else{
                $company_package = 1;
            }    
        }else{
            $company_package = 1;
        }

        $check_permission_id = Permission::where([
            'slug_name' => $check_permission
            ])->first()->id;
        
        $role_allowed = RolePermission::where([
            'fk_role_id' => $role_id,
            'fk_permission_id' => $check_permission_id
        ])->get()->count();
        
        if($role_allowed <= 0 || $company_package<=0){
            \abort('403');
        }
        
        return $next($request);
    }
}
