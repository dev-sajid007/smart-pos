<?php

namespace App\Http\Controllers;

use App\AccountReview;
use App\Purchase;
use App\Supplier;
use App\Transaction;
use Illuminate\Http\Request;
use App\Voucher;
use Auth;
use App\VoucherAccountChart;
use App\AccountChart;
use Illuminate\Support\Facades\DB;

class IncomeExpenseReportController extends Controller
{
    public function index()
    {
        $payments = Transaction::where('amount', '<', 0)->incomeExpenses()
            ->whereBetween('created_at', dateRange())
            ->where('transactionable_type', "App\\AccountReview")
            ->orWhere('transactionable_type', "App\\Purchase")
            ->get()->sum('amount');

        $collections = Transaction::where('amount', '>=', 0)->incomeExpenses()
            ->whereBetween('created_at', dateRange())
            ->where('transactionable_type', "App\\AccountReview")
            ->orWhere('transactionable_type', "App\\Sale")->get()->sum('amount');

        $reports = AccountChart::companies()->with(['transactions' => function($query) {
                                        $query->whereBetween(DB::raw('Date(transactions.created_at)'), dateRange());
                                    }
                                ])->whereHas('transactions')->get();

        return view('admin.reports.income_expense_report', compact('reports', 'payments', 'collections'));
    }
}
