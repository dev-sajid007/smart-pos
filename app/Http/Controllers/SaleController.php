<?php

namespace App\Http\Controllers;

use App\Mail\MailSender;
use App\Models\RandomReturn;
use Illuminate\Http\Request;
use App\Services\SalesService;

use App\ProductSerialSalesDetails;
use Illuminate\Support\Facades\{Auth, Mail, DB};
use App\Http\Requests\{SaleUpdateRequest, SaleRequest};
use App\{CustomerCategory, SoftwareSetting, AccountReview, ProductStock, Transaction, Warehouse, Customer, Account, Product, Company, Currier, marketer, marketers_details, marketing, ProductSerial, Status, Sale};

class SaleController extends Controller
{

    public $balance = 0;
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request)
    {
        $invoiceId = (int)str_ireplace('#S-', '', $request->search);
        $data['customers'] = Customer::pluck('name', 'id');
        $softwareSetting = SoftwareSetting::get();

        $data['isSendSms'] = $softwareSetting->where('title', 'Send Invoice Sms To Customer')->where('options', 'yes')->count() ? true : false;
        $data['isSendMail'] = $softwareSetting->where('title', 'Send Invoice Mail To Customer')->where('options', 'yes')->count() ? true : false;
        $data['isSendSms'] = true;
        $data['sales'] = Sale::companies()->userLog()->with('sale_customer:name,id,phone')
            ->with(['sale_details' => function ($q) {
                $q->select('id', 'fk_sales_id')->withCount('serials');
            }])
            ->latest()
            ->when($request->filled('search'), function ($q) use ($request, $invoiceId) {
                $q->where('id', 'LIKE', "{$invoiceId}%")
                    ->orWhere('sale_date', $request->search);
            })->when($request->filled('customer_id'), function ($q) use ($request) {
                $q->where('fk_customer_id', $request->customer_id);
            })->when($request->filled('invoice_id'), function ($q) use ($request) {
                $invoice_id = $request->invoice_id;

                $invoice_id = substr($invoice_id, -5);
                if ($invoice_id) {
                    $invoice_id = (int)$invoice_id;
                }
                $q->where('id', $invoice_id);
            })->withCount(['sale_details as total_sale_amount' => function ($q) {
                $q->select(DB::Raw('SUM(quantity * unit_price)'));
            }])->paginate(30);
        return view('admin.sales.index', $data);
    }
    public function create(Request $request)
    {
        $data['marketers']          = marketer::get();
        $data['statuses']           = Status::all();
        $data['settings']           = SoftwareSetting::companies()->get();
        $data['couriers']           = Currier::companies()->orderBy('name')->pluck('name', 'id');
        $data['categories']         = CustomerCategory::companies()->get();
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['account_infos']      = Account::getAccountWithBalance();
        $data['customers']          = Customer::orderBy('name')->select('name', 'due_limit', 'current_balance as balance', 'default as is_default', 'id')->get();
        $data['products']           = Product::select('id', 'product_code', 'product_name', 'product_cost', 'product_price', 'has_serial', 'tax')
            ->withCount(['warehouse_stocks as available_quantity' => function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(available_quantity)'));
            }])->get();
        if ($request->type == 'hole-sale') {
            return view('admin.sales.holesale.hole-sale-create', $data);
        }
        return view('admin.sales.create', $data);
    }

    private function getDefaultCustomer()
    {
        return $defaultCustomer = Customer::companies()
            ->where('default', true)
            ->select('id', 'name')
            ->first();
    }

    public function store(SaleRequest $request)
    {

        $sale = $request->storeSale();

        if ($request->filled('is_send_message')) {
            $this->sendSms($sale, $request);
        }
        $url = route('sales.show', $sale->id) . ($request->print_type ? '?print_type=pos-invoice' : '?print_type=invoice');
        return redirect($url)->withSuccess('Sale updated successfully!');
    }

    public function show(Request $request, $id)
    {
        $type = $request->print_type = $request->print_type == 'pos-invoice' ? 'pos-invoice' : 'invoice';

        try {
            $data['settings']   = SoftwareSetting::companies()->get();
            $data['sale']       = Sale::with(['sale_customer'])->withCount(['sale_details as total_sale_amount' => function ($q) {
                $q->select(DB::Raw('SUM(quantity * unit_price)'));
            }])->with(['sale_details' => function ($q) {
                $q->with('product', 'package');
            }])->find($id);
            return view('admin.sales.' . $type, $data);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return back()->withError($ex->getMessage());
        }
    }
    public function edit(Sale $sale)
    {
        $data['sale']               = $sale;
        $data['marketers']          = marketer::get();
        $data['statuses']           = Status::all();
        $data['defaultCustomer']    = $this->getDefaultCustomer();
        $data['categories']         = CustomerCategory::companies()->get();
        $data['account_infos']      = Account::getAccountWithBalance();
        $data['couriers']           = Currier::companies()->orderBy('name')->pluck('name', 'id');
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['customers']          = Customer::orderBy('name')->select('name', 'due_limit', 'current_balance as balance', 'default as is_default', 'id')->get();
        $data['settings']           = SoftwareSetting::companies()->get();
        $data['products']           = Product::get(['id', 'product_code', 'product_name', 'product_cost', 'product_price', 'has_serial', 'tax']);
        return view('admin.sales.edit', $data);

        // return view('admin.sales.edit-sale.edit', $data);
    }
    public function update(SaleUpdateRequest $request, Sale $sale)
    {
        $request->updateSale($sale);

        $url = route('sales.show', $sale->id) . ($request->print_type ? '?print_type=pos-invoice' : '?print_type=invoice');

        return redirect($url)->withSuccess('Sale updated successfully!');
    }
    public function destroy(Sale $sale)
    {
        $customer_id = $sale->fk_customer_id;
        if ($sale->returns()->isEmpty()) {
            try {
                DB::transaction(function () use ($sale) {
                    optional($sale->saleMeta)->delete();
                    foreach ($sale->sale_details as $item) {
                        $productStock = ProductStock::where('fk_product_id', $item->fk_product_id)
                            ->where('fk_company_id', $sale->fk_company_id)->where('warehouse_id', $item->warehouse_id)->first();
                        if ($item->product->has_serial == 1) {
                            ProductSerial::whereIn('id', $item->serials->pluck('id'))->update([
                                'is_sold' => 0
                            ]);
                            ProductSerialSalesDetails::where('sales_details_id', $item->id)->delete();
                        }
                        if ($productStock) {
                            $productStock->update([
                                'sold_quantity' => $productStock->sold_quantity -= $item->quantity,
                                'available_quantity' => $productStock->available_quantity += $item->quantity,
                            ]);
                        }
                    }

                    $sale->sale_details->each->delete();

                    optional($sale->transaction)->delete();
                    try {
                        $sub_total = Sale::where('id', $sale->id)->first()->sub_total;
                        $marketers_details = marketers_details::where('start_amount', '<=', $sub_total)
                            ->where('end_amount', '>=', $sub_total)->first();
                        $percantage = $marketers_details->marketers_commission;
                        $marketers_amount = round(($sub_total * $percantage) / 100);
                        $marketers = marketer::where('id', $sale->marketers_id);
                        if ($marketers->first() != null || $marketers->first() != '') {
                            $balance = $marketers->first()->balance;
                            $old_amount = $balance - $marketers_amount;
                            $marketers->update([
                                'balance' => $old_amount
                            ]);
                        }
                    } catch (\Exception $ex) {
                        dd($ex->getMessage());
                    }
                    $sale->delete();
                });
            } catch (\Exception $ex) {
                return $ex->getMessage();
                return back()->withError($ex->getMessage());
            }
            // update customer balance
            (new SalesService())->updateCustomerBalance($customer_id);
            return \redirect('sales')->withSuccess('Sale Deleted Successfully!');
        }
        return \redirect('sales')->withError('Sale can not be deleted!');
    }




    public function available_quantity(Request $request, $product_id)
    {
        $product_stocks = ProductStock::companies()->with('stock_product')->where('fk_product_id', $product_id)->first() ?? [];
        return response()->json($product_stocks);
    }

    public function getProduct($product_id)
    {
        return response()->json(Product::find($product_id));
    }

    public function account_linked()
    {
        $account_linked = Company::findOrFail(Auth::user()->fk_company_id)->account_linked;
        return $account_linked;
    }

    public function searchProduct(Request $request)
    {
        $product = Product::with('product_stock')
            ->companies()
            ->where('product_name', 'LIKE', "%{$request->name}%")
            ->orWhere('product_code', 'LIKE', "%{$request->name}%")
            ->orWhereHas('barcode', function ($q) use ($request) {
                $q->where('barcode_number', $request->name);
            })
            ->selectRaw('product_name as name, product_code, id, product_price, tax, opening_quantity')
            ->take(20)->get()
            ->map(function ($product) {
                return [
                    'id'                => $product->id,
                    'name'              => $product->name,
                    'product_code'      => $product->product_code,
                    'product_price'     => $product->product_price,
                    'tax'               => $product->tax,
                    'retail_quantity'   => $product->product_stock->available_quantity ?? 0,
                ];
            });
        return response()->json($product);
    }

    public function searchProductByCustomerId(Request $request, $customer_id, $warehouse_id = null)
    {
        $product_like_search = Product::with('product_rak')
            ->with(['warehouse_stocks' => function ($q) use ($warehouse_id) {
                $q->where('warehouse_id', $warehouse_id)->where('fk_company_id', auth()->user()->fk_company_id);
            }])
            ->selectRaw('product_name as name, product_code, id, product_cost, product_price, tax, discount')
            ->where(function ($q) use ($request) {
                $q->where('product_name', 'LIKE', "%" . $request->name . "%")
                    ->orWhere('product_code', 'LIKE', "%" . $request->name . "%")
                    ->orWhereHas('barcode', function ($q) use ($request) {
                        $q->where('barcode_number', $request->name);
                    });
            })

            ->when($customer_id != null, function ($qr) use ($customer_id) {
                $qr->withCount(['pricing as customer_price' => function ($q) use ($customer_id) {
                    $q->where('customer_id', $customer_id)->select(DB::Raw('SUM(price)'));
                }]);
            })
            ->take(10)->get();

        $product = $product_like_search
            ->map(function ($product) {
                $stock = $product->warehouse_stocks->first();

                return [
                    'id'                => $product->id,
                    'name'              => $product->name,
                    'product_code'      => $product->product_code,
                    'product_rak_name'  => optional(optional($product->product_rak)->rak)->name,
                    'product_cost'      => $product->product_cost,
                    'product_price'     => $product->product_price,
                    'tax'               => $product->tax,
                    'discount'          => $product->discount,
                    'retail_quantity'   => optional($stock)->available_quantity ?? 0,
                    'customer_price'    => $product->customer_price > 0 ? $product->customer_price : $product->product_price
                ];
            });
        return response()->json($product);
    }


    public function searchHolesaleProductByCustomerId(Request $request, $customer_id, $warehouse_id = null)
    {
        $product_like_search = Product::with('product_rak')->companies()
            ->with(['warehouse_stocks' => function ($q) use ($warehouse_id) {
                $q->where('warehouse_id', $warehouse_id);
            }])
            ->selectRaw('product_name as name, product_code, id, product_cost, product_price, holesale_price, tax, opening_quantity, discount')
            ->where('product_name', 'LIKE', "%" . $request->name . "%")
            ->orWhere('product_code', 'LIKE', "%" . $request->name . "%")
            ->orWhereHas('barcode', function ($q) use ($request) {
                $q->where('barcode_number', $request->name);
            })
            ->when($customer_id != null, function ($qr) use ($customer_id) {
                $qr->withCount(['pricing as customer_price' => function ($q) use ($customer_id) {
                    $q->where('customer_id', $customer_id)->select(DB::Raw('SUM(price)'));
                }]);
            })
            ->take(10)->get();

        $product = $product_like_search
            ->map(function ($product) {
                $stock = $product->warehouse_stocks->first();
                return [
                    'id'                => $product->id,
                    'name'              => $product->name,
                    'product_code'      => $product->product_code,
                    'product_rak_name'  => optional(optional($product->product_rak)->rak)->name,
                    'product_cost'      => $product->product_cost,
                    'product_price'     => $product->product_price,
                    'tax'               => $product->tax,
                    'discount'          => $product->discount,
                    'retail_quantity'   => optional($stock)->available_quantity ?? 0,
                    'customer_price'    => $product->customer_price > 0 ? $product->customer_price : ($product->holesale_price > 0 ? $product->holesale_price : $product->product_price)
                ];
            });
        return response()->json($product);
    }


    public function getCustomer(Request $request)
    {
        $term = $request->term;
        return $customers = Customer::companies()

            ->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('id', '=', $term)
                    ->orWhere('email', 'LIKE', "%{$term}%")
                    ->orWhere('phone', 'LIKE', "%{$term}%");
            })
            ->take(10)
            ->get()

            ->map(function ($customer) {
                return [
                    'value' => $customer->name,
                    'id' => $customer->id,
                    'phone' => $customer->phone,
                    'customer_code' => $customer->customer_code,
                    'advanced_payment' => $customer->current_balance >= 0 ? $customer->current_balance : 0,
                    'previous_due' => $customer->current_balance <= 0 ? $customer->current_balance : 0,
                ];
            })->toArray();

        return response($customers, 200);
    }

    public function getCustomerBalance($customer_id)
    {

        $customer = Customer::with('customer_category')->find($customer_id);

        $data['previous_due']       = $customer->current_balance >= 0 ? $customer->current_balance : 0;
        $data['advanced_payment']   = $customer->current_balance < 0 ? abs($customer->current_balance) : 0;
        $data['due_limit']          = $customer->due_limit ?? 999999999;
        $data['amount_of']          = optional($customer->customer_category)->amount_of ?? 0;
        $data['amount']             = optional($customer->customer_category)->amount ?? 0;
        $data['type']               = optional($customer->customer_category)->type ?? 'nothing';
        $data['category']           = $customer->customer_category;

        return  response()->json($data);
    }

    public function get_due_collection()
    {
        return view('admin.sales.due-collection');
    }



    public function getSaleReturns($customer_id)
    {
        return $saleReturnAmount = Transaction::companies()->whereHasMorph('transactionable', RandomReturn::class, function ($q) use ($customer_id) {
            $q->where('fk_customer_id', $customer_id);
        })
            ->with('transactionable:amount,id')
            ->get()
            ->map(function ($item) {
                $data['return_amount'] = abs($item->transactionable->amount);
                return $data;
            })->sum('return_amount');
    }


    private function getCustomerTransactionsAmount($customer_id)
    {
        return $transactions = Transaction::companies()->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($customer_id) {
            $q->where('transactionable_type', Customer::class)
                ->where('transactionable_id', $customer_id);
        })->sum('amount');
    }


    private function getCustomerSaleAmount($customer_id)
    {
        return $purchases = Sale::companies()->where('fk_customer_id', $customer_id)
            ->withCount(['sale_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()->sum(function ($item) {
                return ($item->total_amount + $item->invoice_tax + $item->currier_amount - $item->invoice_discount - $item->paid);
            });
    }

    public function sendMessage(Request $request, Sale $sale)
    {
        if (optional($sale->customer)->phone && $request->type == 'sms') {
            $response = $this->sendSms($sale, $request);
            if ($response == 'Success') {
                return back()->withSuccess('Message Successfully send');
            }
        } else if (optional($sale->customer)->email && $request->type == 'email') {

            $data       = Sale::with(['sale_details', 'sale_customer'])->withCount(['sale_details as total_sale_amount' => function ($q) {
                $q->select(DB::Raw('SUM(quantity * unit_price)'));
            }])->findOrFail($sale->id);

            $view_file = "mail.customer-invoice";
            $subject = $sale->invoiceId . " sales invoice";


            try {
                Mail::to(optional($sale->customer)->email)->send(new MailSender($data, $view_file, $subject));
                return back()->withSuccess('Mail Successfully Send');
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }
        return back()->withErrors('Message Can Not Be Send');
    }

    private function sendSms($sale, $request)
    {
        $total_amount = $sale->sale_details->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $request['numbers'] = getValidMobileNumber($sale->customer->phone);
        $request['text']    = 'প্রিয় গ্রাহক আপনার চালান ' . $sale->invoiceId . ' এর ' . $total_amount . ' টাকা রয়েছে';

        return $response = (new SmsSendController())->sendSmsViaHttpGuzzle($request);
    }
}
