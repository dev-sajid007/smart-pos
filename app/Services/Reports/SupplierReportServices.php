<?php


namespace App\Services\Reports;


use App\AccountReview;
use App\Purchase;
use App\PurchaseReturn;
use App\Supplier;
use App\Transaction;
use Carbon\Carbon;

class SupplierReportServices
{
    private $fromDate;
    private $toDate;
    private $allPurchases;
    private $allPurchaseReturns;
    private $allTransactions;
    private $supplierId;
    private $supplier;

    public function __construct()
    {
        $this->fromDate = Carbon::parse(request('from') ?? "2010-01-01")->format('Y-m-d');
        $this->toDate   = Carbon::parse(request('to') ?? now())->format('Y-m-d');
    }

    public function getAllSupplierReportData()
    {
        $request = request();

        if ($request->filled('supplier_id')) {
            $supplier_id    = $request->supplier_id;

            $this->supplierId = $supplier_id;

            // get all purchase and transaction
            $allPurchases               = $this->getPurchases($supplier_id);
            $allPurchaseReturnPaids     = $this->getPurchaseReturnPaids($supplier_id);
            $allPurchaseReturnUnpaids   = $this->getPurchaseReturnUnpaids($supplier_id);
            $allTransactions            = $this->getSupplierTransactions($supplier_id);

            // set data as global
            $this->allPurchases         = $allPurchases;
            $this->allPurchaseReturns   = $allPurchaseReturnPaids;
            $this->purchaseReturnUnpaids= $allPurchaseReturnUnpaids;
            $this->allTransactions      = $allTransactions;

            // get data for filter date
            $purchases              = $allPurchases->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate) ?? collect([]);
            $purchaseReturnPaids    = $allPurchaseReturnPaids->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate) ?? collect([]);
            $purchaseReturnUnpaids  = $allPurchaseReturnUnpaids->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate) ?? collect([]);
            $transactions           = $allTransactions->where('date', '>=', $this->fromDate)->where('date', '<=', $this->toDate) ?? collect([]);

            $supplierData = [];
            if (count($transactions) > 0) {
                $supplierData = $transactions->merge($purchaseReturnPaids);
            } else {
                $supplierData = $purchaseReturnPaids;
            }

            if (count($purchases) > 0) {
                $supplierData = $purchases->merge($supplierData);
            } else {
                $supplierData = $supplierData;
            }

            if (count($purchaseReturnUnpaids) > 0) {
                $supplierData = $purchaseReturnUnpaids->merge($supplierData);
            } else {
                $supplierData = $supplierData;
            }

            return $supplierData;

        }
        return [];

    }

    public function getOpeningBalanceFromDate()
    {

        if (!request('supplier_id')) {
            return 0;
        }
        $supplier       = Supplier::find($this->supplierId);
        $this->supplier = $supplier;


        $openingDue     = optional($supplier)->opening_due ?? 0;

        $fromPurchaseAmount = $this->allPurchases->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        $fromPurchaseReturnAmount = $this->allPurchaseReturns->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        $fromPurchaseReturnUnpaidAmount = $this->purchaseReturnUnpaids->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        $fromTransactionAmount = $this->allTransactions->where('date', '<', $this->fromDate)->sum(function ($item) {
            return $item['debit'] - $item['credit'];
        });

        // calculate opening balance for from date
        return $openingBalance = ($openingDue + $fromPurchaseAmount + $fromTransactionAmount + $fromPurchaseReturnAmount + $fromPurchaseReturnUnpaidAmount);
    }

    public function getSupplier()
    {
        return $this->supplier;
    }



    private function getPurchases($supplier_id)
    {
        return $purchases = Purchase::companies()->where('fk_supplier_id', $supplier_id)
            ->withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()
            ->map(function($item) {
                $data['date'] = Carbon::parse($item->purchase_date)->format('Y-m-d');
                $data['invoice_id'] = $item->invoice_id;
                $data['debit'] = $item->total_amount - $item->invoice_discount;
                $data['credit'] = $item->paid_amount;
                $data['type'] = 'Purchase';

                return $data;
            });
    }

    private function getSupplierTransactions($supplier_id)
    {
        return $transactions = Transaction::companies()->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($supplier_id) {
                $q->where('transactionable_type', Supplier::class)
                    ->where('transactionable_id', $supplier_id);
            })
            ->with('transactionable')
            ->get()->map(function($item) {
                $data['date'] = Carbon::parse($item->transactionable->date)->format('Y-m-d');
                $data['invoice_id'] = $item->transactionable->invoiceId;
                $data['debit'] = 0;
                $data['credit'] = abs($item->amount);
                $data['discount'] = $item->transactionable_type == 'App\AccountReview' ? $item->transactionable->discount : 0;
                $data['type'] = 'Payment';

                return $data;
            });
    }

    private function getPurchaseReturnPaids($supplier_id)
    {
        return $transactions = Transaction::companies()->whereHasMorph('transactionable', PurchaseReturn::class, function ($q) use ($supplier_id) {
                $q->where('supplier_id', $supplier_id)->where('fk_company_id', auth()->user()->fk_company_id);
            })
            ->with('transactionable')
            ->get()->map(function($item) {
                $data['date'] = Carbon::parse($item->transactionable->date)->format('Y-m-d');
                $data['invoice_id'] = $item->transactionable->invoice_id;
                $data['debit'] = abs($item->amount);
                $data['credit'] = $item->transactionable->amount ;
                $data['type'] = 'Purchase Return';

                return $data;
            });
    }

    private function getPurchaseReturnUnpaids($supplier_id)
    {
        return $purchaseReturns = PurchaseReturn::where('supplier_id', $supplier_id)
            ->where('paid_amount', 0)
            ->get()->map(function($item) {
                $data['date'] = Carbon::parse($item->date)->format('Y-m-d');
                $data['invoice_id'] = $item->invoice_id;
                $data['debit'] = 0;
                $data['credit'] = $item->amount;
                $data['type'] = 'Purchase Return';

                return $data;
            });
    }

    //     #############################         SUPPLIER BALANCE      ###############################
    public function getAllPurchaseAmount($supplier_id)
    {
        return $purchases = Purchase::companies()->where('fk_supplier_id', $supplier_id)
            ->withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()->sum(function($item) {
                return  ($item->total_amount - $item->invoice_discount - $item->paid_amount);
            });
    }


    public function getAllPurchaseReturnsPaidAmount($supplier_id)
    {

        return $transactions = Transaction::companies()->whereHasMorph('transactionable', PurchaseReturn::class, function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id)->where('fk_company_id', auth()->user()->fk_company_id);
        })
        ->with('transactionable')
        ->get()->sum(function($item) {
            return$item->transactionable->amount + $item->amount;
        });
    }


    public function allPurchaseReturnUnpaidAmount($supplier_id)
    {
        return $purchaseReturnAmount = PurchaseReturn::where('supplier_id', $supplier_id)->where('paid_amount', 0)->sum('amount');
    }

    public function getSupplierTransactionAmounts($supplier_id)
    {
        return $transactions = -(Transaction::companies()->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($supplier_id) {
            $q->where('transactionable_type', Supplier::class)
                ->where('transactionable_id', $supplier_id);
        })
        ->get()->sum('amount'));
    }

    public function getSuppliersBalances($suppliers)
    {
        return $balance = $suppliers->map(function ($item) {
            $allPurchaseAmount              = $this->getSupplierPurchaseAmount($item->id);
            $allPurchaseReturnPaidAmount    = $this->getAllPurchaseReturnsPaidAmount($item->id);
            $allPurchaseReturnUnpaidAmount  = $this->allPurchaseReturnUnpaidAmount($item->id);
            $allPaymentAmount               = $this->getSupplierPaymentAmount($item->id);
            $openingDueBalance              = optional($item)->opening_due ?? 0;


            $balance = $allPurchaseAmount + $openingDueBalance - $allPurchaseReturnPaidAmount - $allPaymentAmount - $allPurchaseReturnUnpaidAmount;
            // dd($balance, $allPurchaseAmount, $allPurchaseReturnPaidAmount, $allPurchaseReturnUnpaidAmount, $allPaymentAmount, $openingDueBalance);

            $item['balance'] = $balance;
            return $item;

        });
    }


    public function getSupplierPurchaseAmount($supplier_id)
    {

        return $purchases = Purchase::companies()->where('fk_supplier_id', $supplier_id)
            ->withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()
            ->sum(function($item) {
                return  ($item->total_amount - $item->invoice_discount - $item->paid_amount);
            });

        return $purchases = Purchase::companies()->where('fk_supplier_id', $supplier_id)
            ->withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()
            ->sum(function($item) {
                return  ($item->total_amount - $item->invoice_discount - $item->paid_amount);
            });

    }

    public function getSupplierPaymentAmount($supplier_id)
    {

        return $transactions = Transaction::companies()->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($supplier_id) {
            $q->where('transactionable_type', Supplier::class)
                ->where('transactionable_id', $supplier_id);
        })
        ->with('transactionable')
        ->get()->sum(function($item) {
            return abs($item->amount) - ($item->transactionable_type == 'App\AccountReview' ? $item->transactionable->discount : 0);
        });

        $transaction = Transaction::companies()
            ->where('amount', '<', 0)
            ->where(function ($q) use ($supplier_id) {
                $q->where(function ($qr) use($supplier_id) {
                    $qr->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($supplier_id) {
                        $q->where('transactionable_type', Supplier::class)
                            ->where('transactionable_id', $supplier_id);
                    });
                })->orWhere(function ($qr) use($supplier_id) {
                    $qr->whereHasMorph('transactionable', Purchase::class, function ($q) use ($supplier_id) {
                        $q->where('fk_supplier_id', $supplier_id);
                    });
                });
            })->get()->sum(function($item){
                if ($item->transactionable_type == 'App\AccountReview') {
                    return $item->amount - $item->transactionable->discount;
                } else {
                    $item->amount;
                }
            });

            return (-1) * $transaction;

    }
}
