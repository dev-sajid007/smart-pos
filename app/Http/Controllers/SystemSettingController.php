<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        return view('admin.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    public function view_sysytem_setting()
    {
        return view('admin.settings.system_settings');
    }

    public function view_sysytem_logo()
    {
        return view('admin.settings.system_logo');
    }

    public function view_biller_logo()
    {
        return view('admin.settings.biller_logo');
    }

    public function view_categories()
    {
        return view('admin.settings.view_category');
    }

    public function add_categories()
    {
        return view('admin.settings.add_category');
    }

    public function view_sub_categories()
    {
        return view('admin.settings.view_sub_category');
    }

    public function add_sub_categories()
    {
        return view('admin.settings.add_sub_category');
    }

    public function view_warehouses()
    {
        return view('admin.settings.view_warehouses');
    }

    public function add_warehouses()
    {
        return view('admin.settings.add_warehouses');
    }

    public function view_tax_rate()
    {
        return view('admin.settings.view_tax_rate');
	}

    public function add_tax_rate()
    {
        return view('admin.settings.add_tax_rate');
    }
    

    
    public function view_discount()
    {
        return view('admin.settings.view_discount');
    }
    


    public function add_discount()
    {
        return view('admin.settings.add_discount');
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    public function edit(){
        //$data=SystemSetting::
        return view('admin.settings.system_settings');
    }
}
