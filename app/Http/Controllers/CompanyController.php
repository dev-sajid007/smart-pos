<?php

namespace App\Http\Controllers;

use App\Company;
use App\FundTransfer;
use App\Traits\FileSaver;
use Illuminate\Http\Request;
use App\Package;
use App\CompanyPackage;
use Carbon\Carbon;
use Auth;

class CompanyController extends Controller
{
    use FileSaver;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::where('id', auth()->user()->fk_company_id)->paginate(30);
        return view('admin.global_config.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $packages = Package::orderBy('id', 'asc')->pluck('name', 'id');
        return view('admin.global_config.companies.create', compact('packages'));
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
            'name' => 'required|max:160|unique:companies',
            'phone' => 'required|unique:companies',
            'email' => 'required|unique:companies|max:160',
            'company_logo' => 'required'
        ]);

        $company = new Company;
        $company_id = $company->max('id') + 1 ;

        if($request->file('company_logo')){
            $company->company_logo = $this->upload_image($request->file('company_logo'), $company_id);
        }

        $company->name = $request->name;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->address = $request->address;
        $company->account_linked = $request->account_linked;
        $company->save();


        if($company){
            return \redirect('companies')->with(['message'=>'Company Created Successfully!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.global_config.companies.show')->with('company',$company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        $packages = Package::orderBy('id', 'asc')->pluck('name', 'id');
        $package_id = CompanyPackage::where(['fk_company_id' => $id])->first();
        return view('admin.global_config.companies.edit', compact('company', 'package_id', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->validate($request, [
            'name' => 'required|max:160',
            'phone' => 'required|unique:companies,id,'.$request->id,
            'email' => 'required|unique:companies,id,'.$request->id.'|max:160',
        ]);

        $company->update([
                        'name'              => $request->name,
                        'phone'             => $request->phone,
                        'email'             => $request->email,
                        'website'           => $request->website,
                        'address'           => $request->address,
                        'account_linked'    => $request->account_linked
                    ]);
        $this->upload_file($request->company_logo, $company, 'company_logo', 'company');

        return \redirect('companies')->with(['message'=>'Company Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
       try{
           $company->delete();
       }catch (\Exception $e){
           return redirect()->back()->with('error_message', "This Company is use another table you cannot delete it!");
       }

        return redirect('/companies')->with(['message'=>'Company Deleted Success!']);
    }
}
