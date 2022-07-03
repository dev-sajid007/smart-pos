<?php


namespace App\Services\Reports;


use App\AccountReview;
use App\Customer;
use App\Models\RandomReturn;
use App\Sale;
use App\SaleReturn;
use App\Transaction;
use Carbon\Carbon;

class CustomerReportServices
{
    private $fromDate;
    private $toDate;
    private $allSales;
    private $allSaleReturns;
    private $allSaleReturnUnpaids;
    private $allTransactions;
    private $customerId;
    private $customer;

    public function __construct()
    {
        $this->fromDate = Carbon::parse(request('from') ?? "2010-01-01")->format('Y-m-d');
        $this->toDate   = Carbon::parse(request('to') ?? now())->format('Y-m-d');
    }

    public function getAllCustomerReportData()
    {
        $request = request();

        if ($request->filled('customer_id')) {
            $customer_id    = $request->customer_id;

            $this->customerId = $customer_id;

            // get all purchase and transaction
            $allSales               = $this->getAllSales($customer_id);
            $allSaleReturns         = $this->getSaleReturnPaids($customer_id);
            // $allSaleReturnUnpaids   = $this->getSaleReturnUnpaids($customer_id);
            $allTransactions        = $this->getCustomerTransactions($customer_id);

           

            // set data as global
            $this->allSales             = $allSales;
            $this->allSaleReturns   = $allSaleReturns;
            // $this->allSaleReturnUnpaids = $allSaleReturnUnpaids;
            $this->allTransactions      = $allTransactions;

            // get data for filter date
            $sales              = $allSales->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate);
            $saleReturns    = $allSaleReturns->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate);
            // $saleReturnUnpaids  = $allSaleReturnUnpaids->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate);
            $transactions       = $allTransactions->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate);


            $customerData = [];
            if (count($transactions) > 0) {
                $customerData = $transactions->merge($saleReturns);
            } else {
                $customerData = $saleReturns;
            }

            if (count($sales) > 0) {
                $customerData = $sales->merge($customerData);
            } else {
                $customerData = $customerData;
            }
            
            // if (count($saleReturnUnpaids) > 0) {
            //     $customerData = $saleReturnUnpaids->merge($customerData);
            // } else {
            //     $customerData = $customerData;
            // }
            return $customerData->sortByDesc('date');

            return ($sales->merge($transactions->merge($saleReturns)))->sortBy('date');
        }
        return [];

    }

    public function getOpeningBalanceFromDate()
    {
        if (!request('customer_id')) {
            return 0;
        }
        $customer       = Customer::find($this->customerId);
        $this->customer = $customer;
        $openingDue     = $customer->customer_previous_due;

        $fromPurchaseAmount = $this->allSales->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        $fromSaleReturnPaidAmount = $this->allSaleReturns->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        // $fromSaleReturnUnpaidAmount = $this->allSaleReturnUnpaids->where('date', '<', $this->fromDate)->sum(function ($item) {
        //     return $item['debit'] - $item['credit'];
        // });

        $fromTransactionAmount = $this->allTransactions->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        // calculate opening balance for from date
        return $openingBalance = ($openingDue + $fromPurchaseAmount + $fromTransactionAmount + $fromSaleReturnPaidAmount);
    }

    public function getCustomer()
    {
        return $this->customer;
    }



    private function getAllSales($customer_id)
    {
        return $purchases = Sale::companies()
            ->withCount(['sale_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->where('fk_customer_id', $customer_id)->get()

            ->map(function($item) {
                $data['date'] = Carbon::parse($item->sale_date)->format('Y-m-d');
                $data['invoice_id'] = $item->invoiceId;
                $data['source_id']  = $item->id;
                $data['debit']      = $item->total_amount + $item->invoice_tax + $item->currier_amount + ($item->cod_amount ?? 0) - $item->invoice_discount;
                $data['credit']     = $item->paid;
                $data['type']       = 'Sale';

                return $data;
            });
    }

    private function getCustomerTransactions($customer_id)
    {
        return $transactions = Transaction::whereHasMorph('transactionable', AccountReview::class, function ($q) use ($customer_id) {
                $q->where('transactionable_type', Customer::class)
                    ->where('transactionable_id', $customer_id);
            })
            ->with('transactionable')
            ->get()->map(function($item) {
                $data['date'] = Carbon::parse($item->transactionable->date)->format('Y-m-d');
                $data['invoice_id'] = $item->transactionable->invoiceId;
                $data['discount']   = $item->transactionable->discount;
                $data['source_id']  = $item->transactionable_id;
                $data['debit'] = 0;
                $data['credit'] = abs($item->amount);
                $data['type'] = 'Collection';

                return $data;
            });
    }

    private function getSaleReturnPaids($customer_id)
    {
        $transactions = Transaction::whereHasMorph('transactionable', RandomReturn::class, function ($q) use ($customer_id) {
                $q->where('fk_customer_id', $customer_id)->companies();
            })
            ->with('transactionable')
            ->get()
            ->map(function($item) {
                $data['date'] = Carbon::parse($item->transactionable->date)->format('Y-m-d');
                $data['invoice_id'] = $item->transactionable->invoiceId ?? ('#SR-' . (1000000 + $item->transactionable_id));
                $data['source_id']  = $item->transactionable_id;
                $data['debit'] = 0;
                $data['credit'] = abs($item->transactionable->amount);
                $data['type'] = 'Sale Return Payment';

                return $data;
            });


        
        // $transactions2 = Transaction::whereHasMorph('transactionable', SaleReturn::class, function ($q) use ($customer_id) {
        //     $q->where('customer_id', $customer_id);
        // })
        // ->with('transactionable')
        // ->get()
        // ->map(function($item) {
        //     $data['date'] = Carbon::parse($item->transactionable->date)->format('Y-m-d');
        //     $data['invoice_id'] = $item->transactionable->invoice_no ?? ('#SR-' . (1000000 + $item->transactionable_id));
        //     $data['source_id']  = $item->transactionable_id;
        //     $data['debit'] = 0;
        //     $data['credit'] = abs($item->transactionable->amount);
        //     $data['type'] = 'Sale Return Payment';

        //     return $data;
        // });
        $transactions2 = SaleReturn::where('customer_id', $customer_id)
            // ->where('paid_amount', 0)
            ->get()
            ->map(function($item) {
                $data['date']       = fdate($item->date);
                $data['invoice_id'] = $item->invoice_no ?? ('#SR-' . (1000000 + $item->id));
                $data['source_id']  = $item->transactionable_id;
                $data['debit']      = $item->paid_amount;
                $data['credit']     = $item->amount;
                $data['type']       = 'Sale Return';

                return $data;
            });
        if (count($transactions) > 0) {
            return array_merge($transactions, $transactions2);
        }
        return $transactions2;
    }


    private function getSaleReturnUnpaids($customer_id)
    {
        return $transactions = SaleReturn::where('customer_id', $customer_id)->where('paid_amount', 0)
            ->get()
            ->map(function($item) {
                $data['date']       = fdate($item->date);
                $data['invoice_id'] = $item->invoice_no ?? ('#SR-' . (1000000 + $item->id));
                $data['source_id']  = $item->transactionable_id;
                $data['debit']      = $item->amount;
                $data['credit']     = 0;
                $data['type']       = 'Sale Return';

                return $data;
            });
    }

    //     #############################         CUSTOMER BALANCE      ###############################
    public function getAllSalesAmount($customer_id)
    {

        $sales = Sale::companies()
            ->withCount(['sale_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->where('fk_customer_id', $customer_id)->get()
            ->sum(function ($item) {

                // dd($item, ($item->total_amount + $item->invoice_tax + $item->currier_amount + ($item->cod_amount ?? 0) - $item->invoice_discount - $item->paid));
                return $item->total_amount + $item->invoice_tax + $item->currier_amount + ($item->cod_amount ?? 0) - $item->invoice_discount - $item->paid;
            });
            return $sales;

    }

    public function getAllReturnAmount($customer_id)
    {
        return $saleReturns = SaleReturn::where('customer_id', $customer_id)->get()
        // ->sum('amount');
        // dd($saleReturns);
        ->sum(function($item) {

            return $item->amount - $item->paid_amount;
        });
    }

    public function getAllSalesReturnAmount($customer_id)
    {
        $oldReturnAmount = Transaction::whereHasMorph('transactionable', RandomReturn::class, function ($q) use ($customer_id) {
            $q->where('fk_customer_id', $customer_id);
        })
        ->with('transactionable')
        ->get()->sum(function ($item) {
            return - $item->transactionable->amount;
        });
        return $oldReturnAmount;

        $newReturnAmount = Transaction::whereHasMorph('transactionable', SaleReturn::class, function ($q) use ($customer_id) {
            $q->where('customer_id', $customer_id);
        })
        ->with('transactionable')
        ->get()->sum(function ($item) {
            return - $item->transactionable->amount;
        });

        return ($oldReturnAmount + $newReturnAmount);

    }

    public function getCustomerTransactionAmount($customer_id)
    {
        return $transactions = - (Transaction::whereHasMorph('transactionable', AccountReview::class, function ($q) use ($customer_id) {
            $q->where('transactionable_type', Customer::class)
                ->where('transactionable_id', $customer_id);
        })->get()->sum(function ($item) {
            
            return ($item->amount + $item->transactionable->discount);
        }));
    }

    public function getCustomersBalances($customers)
    {
        $balances = $customers->map(function ($item) {
            $allReturnAmount            = $this->getAllReturnAmount($item->id);
            $allSalesAmount             = $this->getAllSalesAmount($item->id);
            $allSalesReturnPaidAmount   = $this->getAllSalesReturnAmount($item->id);
            $allPaymentAmount           = $this->getCustomerTransactionAmount($item->id);
            $openingDueBalance          = optional($item)->customer_previous_due ?? 0;

            $item['balance']            = ($openingDueBalance + $allSalesAmount + $allPaymentAmount + $allSalesReturnPaidAmount - $allReturnAmount);
            
            return $item;
        });
        
        return $balances;
    }
}
