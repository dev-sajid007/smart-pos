<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Auth;
use App\UserRole;
use App\Company;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_id = UserRole::where('user_id', Auth::user()->id)->firstOrFail()->role_id;
        if($role_id == 1){
            $roles = Role::paginate(10);
        }else{
            $roles = Role::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
        }
        return view('admin.user.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role_id = UserRole::where('user_id', Auth::user()->id)->firstOrFail()->role_id;
        if($role_id == 1){
            $companies = Company::orderBy('id', 'asc')->pluck('name', 'id');
        }else{
            $companies = null;
        }
        return view('admin.user.roles.create', compact('companies'));
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
            'name' => 'required|max:160|unique:roles',
            'status' => 'required'
        ]);

        Role::create($request->except('_token'));

        return redirect()->route('roles.index')->with(['success'=>'Role Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $Role)
    {
        return view('admin.user.roles.show', compact('Role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $Role)
    {
        return view('admin.user.roles.edit', compact('Role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:roles,name,'.$request->id,
            'status' => 'required'
        ]);
        $role->update($request->all());
        return redirect()->route('roles.index')->with(['success'=>'Role Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $Role)
    {
        try{
            $Role->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error_message','This Role use in another table you can not to delete it');
        }
        return redirect()->back()->with(['message'=>'Role Deleted Successfully!']);
    }
}
