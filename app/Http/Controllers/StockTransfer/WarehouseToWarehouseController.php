<?php

namespace App\Http\Controllers\StockTransfer;

use App\Product;
use App\ProductStock;
use App\Services\StockTransfer\WarehouseToWarehouseStockTransferService;
use App\StockTransfer;
use App\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class WarehouseToWarehouseController extends Controller
{
    private $service;

    public function __construct(WarehouseToWarehouseStockTransferService $stockTransferService)
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
            ->where('from_company_id', auth()->user()->fk_company_id)
            ->where('to_company_id', auth()->user()->fk_company_id)
            ->paginate(30);
        return view('admin.stock-transfer.warehouses.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['products']           = Product::companies()->select('product_name', 'id', 'fk_product_unit_id', 'product_code')->with('product_unit:name,id')
            ->withCount(['warehouse_stocks as total_available_stock' => function($q) use($request) {
            return $q->where('warehouse_id', $request->from_warehouse_id)->select(DB::raw('SUM(available_quantity)'));
        }])->get();
        return view('admin.stock-transfer.warehouses.create', $data);
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
                $this->service->storeWarehouseToWarehouseStock();

                $this->service->storeWarehouseToWarehouseStockDetails();
            });
            return redirect()->route('warehouse-to-warehouses.show', $this->service->stockTransfer->id)->withSuccess("Stock Transfer Created Successfully!");
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
        $stock = StockTransfer::with('from_warehouse', 'to_warehouse', 'from_company', 'stock_transfer_details.product', 'created_user')->find($id);
        return view('admin.stock-transfer.warehouses.show', compact('stock'));
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
            return redirect()->route('warehouse-to-warehouses.index')->withSuccess("Stock Transfer Deleted Successfully!");
        } catch (\Exception $ex) {
            return back()->withErrors('Stock Deleted not possible');
        }
    }
}
