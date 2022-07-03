<?php

namespace App\Http\Controllers;

use App\Company;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules   = Module::with('company')->get();
        return view('admin.user.modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('admin.user.modules.create', compact('companies'));
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
            'fk_company_id' => 'required'
        ]);

        Module::create([
            'name' => $request->name,
            'fk_company_id' => $request->fk_company_id,
            'no_general' => 1
        ]);

        return redirect()->route('modules.index')->with(['success' => 'Module Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        return view('admin.user.modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:modules,name,'. $module->id,
            'status' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'no_general' => $request->status
        ];

        $module->update($data);
        return redirect()->route('modules.index')->with(['success'=>'Module Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Module::destroy($id);
        }catch (\Exception $e){
            return redirect()->back()->with('error_message', 'This module use in another table you can not to delete it');
        }
        return redirect()->back()->with(['message' => 'Module Deleted Successfully!']);
    }
}
