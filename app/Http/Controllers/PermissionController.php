<?php

namespace App\Http\Controllers;

use App\Module;
use App\Permission;
use Illuminate\Http\Request;
use Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::with('module')->where('fk_company_id', auth()->user()->fk_company_id)->paginate(30);
        return view('admin.user.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::pluck('name', 'id');
        return view('admin.user.permissions.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:permissions',
            'status' => 'required'
        ]);

        $data = [
                'name' => $request->name,
                'slug_name' => $request->slug_name,
                'description' => $request->description,
                'fk_module_id' => $request->fk_module_id,
                'fk_company_id' => auth()->user()->fk_company_id,
                'fk_created_by' => auth()->id(),
                'fk_updated_by' => auth()->id(),
                'status' => 1
            ];
        Permission::create($data);

        return redirect()->route('permissions.index')->with(['message'=>'Permission Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $Permission)
    {
        return view('admin.permissions.show', compact('Permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $modules = Module::pluck('name', 'id');
        return view('admin.user.permissions.edit', compact('permission', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        
        $this->validate($request, [
            'name' => 'required|max:160|unique:permissions,name,' . $permission->id,
            'status' => 'required'
        ]);

        $permission->update($request->all());

        return redirect()->route('permissions.index')->with(['message' => 'Permission Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $Permission)
    {
        $Permission->delete();
        return redirect()->route('permissions.index')->with(['message'=>'Permission Deleted Successfully!']);
    }
}
