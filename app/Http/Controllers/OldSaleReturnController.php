<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\SaleReturnRequest;
use App\Sale;
use App\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OldSaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.return.sales.index', [
            'saleReturns' => SaleReturn::companies()->userLog()->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $request->validate([
            'sale_id' => 'required'
        ]);


        if($request->ajax()){
            return response(
                Sale::with('sale_details.product', 'customer')->find($request->id), 200
            );
        }

        return view('admin.return.sales.create', [
            'sale'          => Sale::find($request->sale_id),
            'account_infos' => Account::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleReturnRequest $request)
    {
        $request->persist();
        return redirect('sale-returns')->withSuccess('Sale Return Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturn $saleReturn)
    {
        return view('admin.return.sales.show', compact('saleReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($saleReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $saleReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(SaleReturn $saleReturn)
    {
        DB::transaction(function ()use($saleReturn){
            $saleReturn->delete();
        });

        return back()->withSuccess('Sale Return Reverted Successfully!');
    }

    public function getAllSales(Request $request)
    {
        return Sale::where('fk_company_id', companyId())
            ->where('id', 'like', "%{$request->name}%")
            ->orWhere('sale_reference', 'like', "%{$request->name}%")
            ->orWhereHas('customer', function ($query)use($request){
                $query->where('name', 'like', "%{$request->name}%");
            })
            ->take(10)
            ->get()->map(function ($sale){
                $saleId = str_pad($sale->id, 6, '0', 0);
                return [
                    'id' => $sale->id,
                    'name' => "Invoice ID: #{$saleId} | Reference: {$sale->sale_reference} | {$sale->sale_date->format('d-m-Y')}"
                ];
            });
    }
}
