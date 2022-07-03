<?php

namespace App\Http\Controllers\StockTransfer;

use App\Company;
use App\Product;
use App\ProductStock;
use App\Services\StockTransfer\CompanyToCompanyStockTransferService;
use App\StockTransfer;
use App\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CompanyToCompanyController extends Controller
{
    private $service;

    public function __construct(CompanyToCompanyStockTransferService $stockTransferService)
    {
        $this->service = $stockTransferService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = StockTransfer::with('from_warehouse', 'to_warehouse', 'created_user')
            ->where(function ($q) {
                $q->where('from_company_id', auth()->user()->fk_company_id)
                ->orWhere('to_company_id', auth()->user()->fk_company_id);
            })
            ->whereColumn('from_company_id', '!=', 'to_company_id')
            ->paginate(30);
        return view('admin.stock-transfer.companies.index', compact('stocks'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['companies']  = Company::whereNotIn('id', [auth()->user()->fk_company_id])->pluck('name', 'id');
        $data['warehouses'] = Warehouse::companies()->pluck('name', 'id');
        $data['products']   = Product::select('product_name', 'id', 'fk_product_unit_id', 'product_code')
                                        ->with('product_unit:name,id')
                                        ->withCount(['warehouse_stocks as total_available_stock' => function($q) use($request) {
                                            return $q->where('warehouse_id', $request->select_warehouse_id)->select(DB::raw('SUM(available_quantity)'));
                                        }])->get();

        return view('admin.stock-transfer.companies.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () {
                $this->service->storeStockTransfer();

                $this->service->storeStockTransferDetails();
            });
            return redirect()->route('company-to-companies.show', $this->service->stockTransfer->id)->withSuccess("Stock Successfully  Transfered!");
        } catch (\Exception $ex) {
            return back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouses = Warehouse::companies()->pluck('name', 'id');
        $stock = StockTransfer::with('from_warehouse', 'to_warehouse', 'from_company', 'to_company', 'stock_transfer_details.product', 'created_user')->find($id);
        return view('admin.stock-transfer.companies.show', compact('stock', 'warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receiveStock($id)
    {
        $this->service->receiveStock($id);
        return back()->withSuccess('Stock Receive Success')->withMessage('Stock Receive Success');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->service->deleteStockTransfer($id);
            return redirect()->route('company-to-companies.index')->withSuccess("Stock Transfer Deleted Successfully!");
        } catch (\Exception $ex) {
            return back()->withErrors('Stock Deleted not possible');
        }
    }
}
