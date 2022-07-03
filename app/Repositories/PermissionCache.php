<?php
namespace App\Repositories;

use App\Permission;
use App\Role;
use Carbon\Carbon;

class PermissionCache
{
    public function allSlugs()
    {
        $cacheKey = 'all.slugs';
        return cache()->remember($cacheKey, Carbon::now()->addDay(), function () {
            return Permission::pluck('slug_name')->toArray();
        });
    }

    public function allPermittedSlugs()
    {
        $cacheKey = 'all.permitted.slugs';
//        return cache()->remember($cacheKey, Carbon::now()->addDay(), function () {
            $user_role = auth()->user()->user_role;
            if ($user_role) {
                $role = Role::find($user_role->role_id);
                if ($role->id == 1) {
                    return $this->allSlugs();
                }
                return $role->permissions()->pluck('slug_name')->toArray();
            }
            return [];
//        });
    }

    public function getCacheKey($key)
    {
        $key = strtolower($key);
        return self::CACHE_KEY."$key";
    }
}