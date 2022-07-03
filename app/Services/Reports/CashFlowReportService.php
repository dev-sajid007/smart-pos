<?php


namespace App\Services\Reports;


use App\Account;
use App\Transaction;

class CashFlowReportService
{
    public function getCashFlowReportData($request)
    {
        $data['openingBalance'] = 0;

        $query = Transaction::companies()->orderBy('date');
        if ($request->filled('account_id')) {
            $query->where('fk_account_id', $request->account_id);
        }
        if ($request->filled('from')) {
            $trans = Transaction::where('fk_company_id', companyId())->orderBy('date');
            if ($request->filled('account_id')) {
                $trans->where('fk_account_id', $request->account_id);
            }
            $openingBalance = $trans->where('date', '<', $request->from)->get();
            $data['openingBalance'] = $openingBalance->sum('amount');
        }
        if ($request->filled('from')) {
            $query->where('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', $request->to);
        }
        $data['transactions']   = $query->get();

        $data['accounts'] = Account::companies()->pluck('account_name','id');
        return $data;
    }
}
