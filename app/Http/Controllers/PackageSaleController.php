<?php

namespace App\Http\Controllers;

use App\Account;
use App\Currier;
use App\Customer;
use App\CustomerCategory;
use App\ProductPackage;
use App\Services\PackageSaleService;
use App\SoftwareSetting;
use App\Status;
use App\Warehouse;
use Illuminate\Http\Request;

class PackageSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['statuses']           = Status::all();
        $data['settings']           = SoftwareSetting::companies()->get();
        $data['couriers']           = Currier::companies()->orderBy('name')->pluck('name', 'id');
        $data['categories']         = CustomerCategory::companies()->get();
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['account_infos']      = Account::getAccountWithBalance();
        $data['customers']          = Customer::companies()->select( 'id', 'name', 'default', 'current_balance as balance', 'due_limit')->get();
        $data['productPackages']    = ProductPackage::orderBy('name')->get();

        return view('admin.sales.package-sales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new PackageSaleService();

        $service->storeSale($request);

        // if ($request->filled('is_send_message')) {
        //     $this->sendSms($service->sale, $request);
        // }

        $url = route('sales.show', $service->sale->id) . ($request->print_type ? '?print_type=pos-invoice' : '?print_type=invoice');

        return redirect($url)->withSuccess('Sale created successfully!');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
