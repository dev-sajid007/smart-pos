<?php

namespace App\Http\Controllers\Reports;

use App\Customer;
use App\Services\Reports\CashFlowReportService;
use App\Services\Reports\CustomerReportServices;
use App\Services\Reports\SupplierReportServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Supplier;

class FinancialReportController extends Controller
{
    public function cashFlowReport(Request $request)
    {
        $data = (new CashFlowReportService())->getCashFlowReportData($request);
        return view('admin.reports.cash_reports', $data);
    }

    //  ###########################################################     customer report  ################################################
    public function customerReports(Request $request)
    {
        $service    = new CustomerReportServices();

        $data['transactions']   = $service->getAllCustomerReportData();
        $data['openingBalance'] = $service->getOpeningBalanceFromDate();
        $data['customer']       = $service->getCustomer();
        $data['customers']      = Customer::companies()->pluck('name', 'id');

        return view('admin.reports.financial-reports.customer-reports', $data);
    }


    //  ###########################################################     supplier report  ################################################
    public function supplierReports(Request $request)
    {
        $service = new SupplierReportServices();

        $data['transactions']   = $service->getAllSupplierReportData();

        $data['openingBalance'] = $service->getOpeningBalanceFromDate();
        $data['supplier']       = $service->getSupplier();
        $data['suppliers']      = Supplier::suppliers()->pluck('name', 'id');

        return view('admin.reports.financial-reports.supplier-reports', $data);
    }

    public function receivableDueReport(Request $request)
    {
        $customers = Customer::companies()->orderBy('name')->get();

        $service = new CustomerReportServices();

        $customerBalances = (object)$service->getCustomersBalances($customers);

        if ($request->type == 'update') {
            foreach ($customerBalances as $key => $customer) {
                Customer::find($customer->id)->update(['current_balance' => $customer->balance]);
            }
            $customers = Customer::companies()->orderBy('name')->get(['name', 'current_balance', 'id']);
        }

        return view('admin.reports.financial-reports.receivable-due-reports', compact('customerBalances', 'customers'));
    }

    public function getPayableDueReport(Request $request)
    {
        $service    = new SupplierReportServices();
        $suppliers = $service->getSuppliersBalances(Supplier::companies()->get());

        $supplierBalances = (object)$suppliers;


        if ($request->type == 'update') {
            foreach ($supplierBalances as $key => $supplier) {
                Supplier::find($supplier->id)->update(['current_balance' => $supplier->balance]);
            }
        }

        $suppliers = Supplier::companies()->get();
        return view('admin.reports.financial-reports.payable-due-reports', compact('supplierBalances', 'suppliers'));
    }
}
