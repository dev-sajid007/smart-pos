<?php

namespace App\Http\Controllers;

use App\CompanyPackage;
use Illuminate\Http\Request;
use App\Company;
use App\Package;
use Carbon\Carbon;
use App\BillingCycle;
use Auth;
use App\SoftwarePayment;

class CompanyPackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_packages = CompanyPackage::with('company')->paginate(30);
        return view('admin.global_config.company_packages.index', compact(
            ['company_packages']
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::orderBy('id', 'asc')->pluck('name', 'id');
        $billing_cycles = BillingCycle::orderBy('id', 'asc')->pluck('name', 'id');

        return view('admin.global_config.company_packages.create', compact([
            'companies', 'billing_cycles'
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
            'fk_company_id' => 'required|not_in:0',
            'fk_billing_cycle_id' => 'required|not_in:0',
            'amount' => 'required|min:0'
        ]);


        $billing_cycle_id = $request->fk_billing_cycle_id;
        $billing_cycle = BillingCycle::findOrFail($billing_cycle_id);
        $trial_period = $request->trial_period;
        $billing_cycle_days = $billing_cycle->days;

        $today = Carbon::today();
        $trial_ends_at = $today->addDays($trial_period)->toDateString();
        
        if($request->amount > 0){
            $ends_at = $today->addDays($billing_cycle_days)->toDateString();
        }else{
            $ends_at = $trial_ends_at;
        }

        $pending_billing = CompanyPackage::where([
            'fk_company_id' => $request->fk_company_id,
            'status' => 0
            ])->get()->count();
        if($pending_billing<=0){
            $company_package = CompanyPackage::create([
                'fk_created_by' => Auth::user()->id,
                'fk_updated_by' => Auth::user()->id,
                'fk_company_id' => $request->fk_company_id,
                'fk_billing_cycle_id' => $request->fk_billing_cycle_id,
                'trial_ends_at' => $trial_ends_at,
                'ends_at' => $ends_at,
                'amount' => $request->amount,
                'trial_period' => $request->trial_period,
                'alert_before' => $request->alert_before,
                'description' => $request->description,
                'status' => $request->status
            ]); 

            $software_billing = SoftwarePayment::create([
                'fk_created_by' => $request->fk_created_by,
                'fk_updated_by' => $request->fk_updated_by,
                'fk_company_id' => Auth::user()->fk_company_id,
                'fk_company_package_id' => $company_package->id,
                'software_payment_date' => $request->software_payment_date,
                'paid_amount' => $request->amount,
                'status' => '0'
            ]);
    
    
            if($company_package && $software_billing){
                return \redirect('company_packages')->with(['message' => 'Company Billing Added Successfully!']);
            }
    
        }else{
            return \redirect('company_packages')->with(['error_message' => 'This Company Has Already Pending Billing!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyPackage  $companyPackage
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyPackage $companyPackage)
    {
        return view('admin.global_config.company_packages.show', compact('companyPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyPackage  $companyPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyPackage $companyPackage)
    {
        $companies = Company::orderBy('id', 'asc')->pluck('name', 'id');
        $billing_cycles = BillingCycle::orderBy('id', 'asc')->pluck('name', 'id');
        return view('admin.global_config.company_packages.edit', compact([
            'companyPackage', 'companies', 'billing_cycles'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyPackage  $companyPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyPackage $companyPackage)
    {
        $this->validate($request, [
            'fk_company_id' => 'required',
            'fk_billing_cycle_id' => 'required',
            'amount' => 'required|min:0'
        ]);

        $billing_cycle_id = $request->fk_billing_cycle_id;
        $billing_cycle = BillingCycle::findOrFail($billing_cycle_id);
        $billing_cycle_days = $billing_cycle->days;

        $today = Carbon::today();
        $trial_ends_at = $today->addDays($request->trial_period)->toDateString();
        if($request->amount > 0){
            $ends_at = $today->addDays($billing_cycle_days)->toDateString();
        }else{
            $ends_at = $trial_ends_at;
        }
        

        if($companyPackage->status != 1){
            $company_package = $companyPackage->update([
                'fk_company_id' => $request->fk_company_id,
                'fk_billing_cycle_id' => $request->fk_billing_cycle_id,
                'trial_ends_at' => $trial_ends_at,
                'ends_at' => $ends_at,
                'amount' => $request->amount,
                'trial_period' => $request->trial_period,
                'alert_before' => $request->alert_before,
                'description' => $request->description,
                'status' => $request->status
            ]);

            $software_payment = SoftwarePayment::where([
                'fk_company_package_id' => $companyPackage->id,
                'status' => '0'
            ]);
            if($software_payment->count() <= 0){
                return redirect('company_packages')->with(['message' => 'Software Payment Not Exist!']);
            }
            $software_payment_update = $software_payment->first()->update([
                'fk_company_id' => $request->fk_company_id,
                'software_payment_date' => $request->software_payment_date,
                'paid_amount' => $request->amount
            ]);

            return \redirect('company_packages')->with(['message' =>'Company Billing Updated Successfully!']);
        }else{
            return \redirect('company_packages')->with(['error_message' =>'You can not change approved billing!']);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyPackage  $companyPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyPackage $companyPackage)
    {
        if($companyPackage->status != 1){
            $company_package_delete = $companyPackage->delete();
            if($company_package_delete){
                return \redirect('company_packages')->with(['message' => 'Company Package Deleted Successfully!']);
            }
        }
    }
}
