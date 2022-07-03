<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use Auth;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_roles = UserRole::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
        return view('admin.user_roles.index', compact('user_roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('fk_company_id', Auth::user()->fk_company_id)->get();
        $roles = Role::where('fk_company_id', Auth::user()->fk_company_id)->get();
        return view('admin.user_roles.create', compact([
            'users', 'roles'
        ]));
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
            'name' => 'required|max:160|unique:user_roles',
            'status' => 'required'
        ]);

        UserRole::create($request->except('_token'));

        return redirect('settings/user_roles')->with(['success'=>'User Role Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        return view('admin.user_roles.show', compact('userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userRole)
    {
        return view('admin.user_roles.edit', compact('userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        
        $this->validate($request, [
            'name' => 'required|max:160|unique:user_roles,name,'.$request->id,
            'status' => 'required'
        ]);

        $userRole->update($request->all());

        return \redirect('settings/user_roles')->with(['message'=>'User Role Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        try{
            $userRole->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error_message','This Role use in another table you can not to delete it');
        }

        return \redirect('settings/user_roles')->with(['message'=>'User Role Deleted Successfully!']);
    }
}
