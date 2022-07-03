<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use App\BillingCycle;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::paginate(30);
        return view('admin.settings.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $billing_cycles = BillingCycle::orderBy('id', 'asc')->pluck('name', 'id');
        return view('admin.settings.packages.create', compact('billing_cycles'));
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
            'name' => 'required|unique:packages|min:3|max:160',
            'fk_billing_cycle_id' => 'required|not_in:0',
            'trial_period' => 'required|numeric|min:1|max:100',
            'alert_before' => 'required|numeric|min:1|max:100'
        ]);

        $package = Package::create($request->except('_token'));
        if($package){
            return redirect('packages')->with(['message' => 'Package Added Successfully!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return view('admin.settings.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {   $billing_cycles = BillingCycle::orderBy('id', 'asc')->pluck('name', 'id');  
        return view('admin.settings.packages.edit', compact('package', 'billing_cycles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $this->validate($request, [
            'name' => 'required|unique:packages,id,'.$request->name.'|min:3|max:160',
            'fk_billing_cycle_id' => 'required|not_in:0',
            'trial_period' => 'required|numeric|min:1|max:100',
            'alert_before' => 'required|numeric|min:1|max:100'
        ]);

        $package_update = $package->update($request->except('_token', 'fk_created_by'));
        if($package_update){
            return \redirect('packages')->with(['message'=>'Package Updated Successfully!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $package_delete = $package->delete();
        if($package_delete){
            return redirect('packages')->with(['message'=>'Package Deleted Successfully!']);
        }
    }
}
