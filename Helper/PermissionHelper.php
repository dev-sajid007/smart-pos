<?php
    use App\Models\Permission\Permission;
    use Facades\App\Repositories\PermissionCache;

    function console_log($output)
    {
        echo "<script> console.log(" . json_encode($output, JSON_HEX_TAG) . "); </script>";
    }
    function console_table($output) {
        echo "<script> console.table(" . json_encode($output, JSON_HEX_TAG) . "); </script>";
    }


    function hasPermission ($slug)
    {
        if (auth()->id() == 1) {
            return true;
        }
        if (is_array($slug)) {
            // <!-- get slugs from session and session stored in App service provider -->
            if(count(array_intersect(PermissionCache::allPermittedSlugs(), $slug)) !== 0) {
                return true;
            }
            return false;
        }
        return in_array($slug, PermissionCache::allPermittedSlugs());
    }




        // <!-- check user has the permission  -->
        function checkIsPermitted()
        {
            $routeName = \Request::route()->getName();
            if (auth()->id() != 1 && $routeName != null) { // not super admin and route name not define
                if (in_array($routeName, PermissionCache::allSlugs())) {   // is create any permission for this route
                    if (!in_array($routeName, PermissionCache::allPermittedSlugs())) {
                        return false;
                    }
                }
            }
            return true;
        }



