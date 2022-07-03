<?php

namespace App\Http\Controllers;

use App\RolePermission;
use Illuminate\Http\Request;
use Auth;
use App\Permission;
use App\Module;
use App\Role;
use App\UserRole;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_permissions = Role::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
        return view('admin.role_permissions.index', compact('role_permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function permissionAssignStore(Request $request, $id)
    {
        $role =  Role::find($id);
        $role->permissions()->sync($request->checked_permission_ids);
        return redirect()->back()->with(['success'=>'RolePermission Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RolePermission  $RolePermission
     * @return \Illuminate\Http\Response
     */
    public function show(RolePermission $RolePermission)
    {
        return view('admin.role_permissions.show', compact('RolePermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RolePermission  $RolePermission
     * @return \Illuminate\Http\Response
     */
    public function permissionAssign($role_id)
    {
        $data['modules'] = Module::with('permissions')->get();
        if ($role_id) {
            $data['role'] = Role::find($role_id);
            $data['isPermitted'] = $data['role']->permissions()->pluck('slug_name')->toArray();
        }
        return view('admin.user.role-permissions.assign', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RolePermission  $RolePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RolePermission $RolePermission)
    {
        
        $this->validate($request, [
            'name' => 'required|max:160|unique:role_permissions,name,'.$request->id,
            'status' => 'required'
        ]);

        $RolePermission->update($request->all());

        return \redirect('role_permissions')->with(['success'=>'RolePermission Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RolePermission  $RolePermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(RolePermission $RolePermission)
    {
        $RolePermission->delete();
        return \redirect('role_permissions')->with(['success'=>'RolePermission Deleted Successfully!']);
    }

    public function get_id_by_role_permission(Request $request){
        $role_id = $request->role_id; 
        $permission_id = $request->permission_id;

        $id = RolePermission::where([
            'fk_company_id' => Auth::user()->fk_company_id,
            'fk_role_id' => $role_id,
            'fk_permission_id' => $permission_id
        ])->firstOrFail()->id;

        return response()->json($id);
    }

    public function assign_rp(Request $request){
        if($request->ajax()){
            
            $role_permission_exist = RolePermission::where([
                'fk_company_id' => Auth::user()->fk_company_id,
                'fk_role_id' => $request->fk_role_id,
                'fk_permission_id' => $request->fk_permission_id
            ])->get();
            if(count($role_permission_exist) >= 1){
                $role_permission_exist->first()->delete();
            }else{
                RolePermission::create($request->except(['_token','checked_permission_ids']));
            }
        }
    }

    public function change(Request $request){
        if($request->ajax()){
            // return $request;
            $role_permission_exist = RolePermission::where([
                'fk_company_id' => Auth::user()->fk_company_id,
                'fk_role_id' => $request->fk_role_id
            ])->pluck('id')->toArray();
            // if($role_permission_exist >= 1){
            //     $role_permission_exist->delete();
            // }else{
            //     RolePermission::create($request->except('_token'));
            // }
            // dd($role_permission_exist);
                $role = Role::findOrFail($request->fk_role_id);
                return $request;
            foreach($request->checked_permission_ids as $checked_permission_id){
                $role->permissions()->sync($checked_permission_id);
            }
            //$role_permission_exist->sync($request->except('_token'));
        }
    }
}
