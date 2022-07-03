<?php

namespace App\Http\Controllers;

use App\Services\SalesService;
use Illuminate\Http\Request;
use App\Models\RandomReturn;
use App\ProductStock;
use App\SaleReturn;
use App\Customer;
use App\Product;
use App\Account;
use App\Models\Inventory\ProductDamage;
use App\Models\Inventory\ProductDamageItem;
use App\ProductSerial;
use App\Sale;
use App\SalesDetails;
use Carbon\Carbon;
use DB;

class SaleReturnController extends Controller
{

    public function index()
    {
        $data['randomReturns'] = RandomReturn::companies()->userLog()->orderByDesc('id');
        $data['saleReturns'] = SaleReturn::companies()->userLog()->orderByDesc('id')->paginate(30);

        return view('admin.return.sales.index', $data);
    }



    public function create()
    {
        $data['customers']  = Customer::companies()->select( 'id', 'name', 'default', 'current_balance as balance', 'due_limit')->get();
        $data['accounts']   = Account::getAccountWithBalance();


        return view('admin.return.sales.create', $data);
    }



    public function store(Request $request)
    {
        DB::transaction(function () use($request) {

            $invoice = $this->makeInvoice($request);


            $this->storeSaleReturnDetails($invoice, $request);


            $this->makeTransaction($invoice, $request);
        });

        // update customer balance
        (new SalesService())->updateCustomerBalance($request->customer_id);

        return back()->withSuccess('Product Returned Successfully');
    }


    private function makeInvoice($request)
    {

        $saleReturn = SaleReturn::create([
            'date'          => fdate(today(), 'Y-m-d'),
            'customer_id'   => $request->customer_id,
            'amount'        => $request->total_amount,
            'reference'     => $request->reference,
            'comment'       => $request->comment,
            'paid_amount'   => $request->return_amount,
            'previous_due'  => $request->previous_due,
        ]);

        $saleReturn->update([
            'invoice_no' => '#SR-' . (1000000 + $saleReturn->id)
        ]);
        return $saleReturn;
    }



    private function storeSaleReturnDetails($invoice, $request)
    {
        // dd($request->all());
        foreach ($request->product_ids as $key => $product_id) {
            $item = $invoice->sale_return_details()->create([
                'product_id'        =>  $product_id,
                'quantity'          =>  $request->quantities[$key],
                'price'             =>  $request->prices[$key],
            ]);
            if($request->condition_type[$key]=='Good'){
                 $this->stockManage($item);
                // dd($request->condition_type[$key]);
            }
            else{
                $this->makeWastage($request, $key);
            }
        }
    }

    private function stockManage($item)
    {
        $stock = ProductStock::where('fk_product_id', $item->product_id)->where('fk_company_id', auth()->user()->fk_company_id)->whereNull('warehouse_id')->first();

        if (!$stock) {
            $stock = ProductStock::create([
                'fk_product_id'         => $item->product_id,
                'sold_quantity'         => -$item->quantity,
                'available_quantity'    => $item->quantity
            ]);
        } else {
            $stock->increment('available_quantity', $item->quantity);
            $stock->decrement('sold_quantity', $item->quantity);
        }
    }

    private function makeWastage($request,$key)
    {
           $product_damage= ProductDamage::create([
                'date'      => Carbon::now()->format('y-m-d'),
                'amount'    => $request->prices[$key],
            ]);
            $damagedItem = ProductDamageItem::create([
                'product_damage_id' =>$product_damage->id,
                'fk_product_id' => $request->product_ids[$key],
                'description'   => $request->comment,
                'type'          => 'damaged',
                'quantity'      => $request->quantities[$key],
                'price'         => $request->prices[$key],
            ]);

            $wastage = $damagedItem->wastage()->create([
                'wastagable_type'   => ProductDamage::class,
                'wastagable_id'     => $product_damage->id,
                'quantity'          => $request->quantities[$key],
            ]);
    }

    private function makeTransaction($invoice, $request)
    {
        if ($invoice->paid_amount > 0) {
            $transaction = $invoice->transaction()->create([
                'fk_account_id' => defaultAccount()->id,
                'date'          => $invoice->date,
                'amount'        => $invoice->paid_amount
            ]);
        }
    }



    public function show(SaleReturn $saleReturn)
    {
        return view('admin.return.sales.show', compact('saleReturn'));
    }




    public function destroy($id)
    {
        try {
            \DB::transaction(function () use ($id) {
                $saleReturn = SaleReturn::find($id);
                $customer_id = $saleReturn->customer_id;

                $saleReturn->transaction()->delete();

                foreach ($saleReturn->sale_return_details as $key => $detail) {
                    $this->updateStock($detail);
                    $detail->delete();
                }
                $saleReturn->delete();

                (new SalesService())->updateCustomerBalance($customer_id);
            });
            return back()->withSuccess('Sale Return Deleted Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }



    private function updateStock($item)
    {
        $stock = ProductStock::companies()->where('fk_product_id', $item->product_id)->whereNull('warehouse_id')->first();

        if ($stock) {
            $stock->decrement('available_quantity', $item->quantity);
            $stock->increment('purchased_quantity', $item->quantity);
            $stock->save();
        }
    }

    public function getCustomerInvoices(Request $request)
    {
        return Sale::where('fk_customer_id', $request->customer_id)->get()->map(function($sale) {
            $data['id'] = $sale->id;
            $data['invoice_no'] = '#S-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);
            return $data;
        });
    }

    public function getInvoiceId(Request $request)
    {
        $product_id = ProductSerial::where('serial', $request->serial_id)->first()->product_id;
        $fk_sales_id = SalesDetails::where('fk_product_id', $product_id)->first()->fk_sales_id;
        $sale = Sale::where('id', $fk_sales_id)->first();

        $data['saleId']          = $sale->id;
        $data['customer_id'] = $sale->fk_customer_id;
        $data['invoice_no']  = '#S-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);
        return $data;
    }
    public function getCustomerData(Request $request)
    {
        $Customer = Customer::where('id',$request->customer_id)->first();
        return $Customer;
    }
    public function getCustomerBuyingProducts(Request $request)
    {
        if($request->invoice_id != '') {

            $sale = Sale::find($request->invoice_id);


            return Product::withCount(['sales_details as quantity' => function($q) use($request) {
                                return $q->where('fk_sales_id', $request->invoice_id)->select(\DB::Raw('SUM(quantity)'));
                            }])->whereHas('sales_details', function($q) use($request) {
                                $q->where('fk_sales_id', $request->invoice_id);
                            })->get()->map(function($item) use($sale) {
                                $data['id']             = $item->id;
                                $data['name']           = $item->product_name;
                                $data['code']           = $item->product_code;
                                $data['price']          = $item->product_price;
                                $data['quantity']       = $item->quantity;
                                $data['warranty_left']  = '';
                                $data['guarantee_left'] = '';

                                if ($item->warranty_days > 0) {
                                    $left_days = Carbon::parse($sale->sale_date)->addDays($item->warranty_days)->diffInDays(Carbon::parse(now()));
                                    if($left_days >= 0) {
                                       $data['warranty_left'] = 'Warranty left ' . $left_days . ' days';
                                    } else {
                                        $data['warranty_left'] = 'Warranty Period Expired';
                                    }
                                }
                                if ($item->guarantee_days > 0) {
                                    $left_days = Carbon::parse($sale->sale_date)->addDays($item->guarantee_days)->diffInDays(Carbon::parse(now()));
                                    if($left_days >= 0) {
                                       $data['guarantee_left'] = 'Guarantee left ' . $left_days . ' days';
                                    } else {
                                        $data['guarantee_left'] = 'Guarantee Period Expired';
                                    }
                                }
                                return $data;
                            });

        } else {
            return Product::withCount(['sales_details as quantity' => function($q) use($request) {
                    $q->whereHas('sale', function($qr) use($request) {
                        $qr->where('fk_customer_id', $request->customer_id);
                    })->select(\DB::Raw('SUM(quantity)'));
            }])
            ->with(['product_stock'=>function($query){
                return $query->select('available_quantity','fk_product_id');
            }])
            // sale return relationship
            // ->whereHas('sales_details', function($q) use($request) {
            //     $q->whereHas('sale', function($qr) use($request) {
            //         $qr->where('fk_customer_id', $request->customer_id);
            //     });
            // })
            ->get()->map(function($item) {
                $data['id']             = $item->id;
                $data['name']           = $item->product_name;
                $data['code']           = $item->product_code;
                $data['price']          = $item->product_price;
                $data['quantity']       = $item->quantity;
                $data['product_stock']  = $item->product_stock->available_quantity;
                $data['warranty_left']  = '';
                $data['guarantee_left'] = '';

                return $data;

            });
        }
    }
}
