<?php


function accountLinked()
{
    return auth()->user()->company->account_linked;
}

function companyId()
{
    return auth()->user()->fk_company_id;
}

function defaultAccount()
{
    $account = App\Account::where([
        'fk_company_id' => companyId(),
        'default_account' => 1
    ])->first();

    return $account;
}

function flipDate($date)
{
    return date('Y-m-d', strtotime($date));
}

function dateRange()
{
    $request = request();
    return [
        'from' => $request->from ? $request->from : date('Y-m-d'),
        'to' => $request->to ? $request->to : date('Y-m-d'),
    ];
}