<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountReview;
use App\Customer;
use App\Http\Requests\DueRequest;
use App\PaymentMethod;
use App\Services\PurchaseService;
use App\Services\Reports\CustomerReportServices;
use App\Services\SalesService;
use App\Supplier;
use Illuminate\Http\Request;

class DueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->type) {
            $reviews = AccountReview::userLog()->whereHasMorph('transactionable', $request->type == 'customer' ? [Customer::class] : [Supplier::class], function ($q) {
                $q->where('fk_company_id', auth()->user()->fk_company_id);
            })
            ->with('account', 'payment_method')
            ->orderByDesc('id')
            ->paginate();

            return view('admin.due.index', compact('reviews'));
        }
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $accounts       = Account::getAccountWithBalance();
        $paymentMethods = PaymentMethod::companies()->where('status', 1)->pluck('method_name', 'id');

        if($request->type == 'customer') {
            return view('admin.due.customer', compact('accounts', 'paymentMethods'));
        }
        if($request->type == 'supplier') {
            return view('admin.due.supplier', compact('accounts', 'paymentMethods'));
        }
        abort(404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DueRequest $request)
    {

        $request->persist();

        $message = 'Transaction Waiting for Approval Successful!';

        if ($request->type == 'customer') {
            $message = 'Customer Payment Successfully';

            (new SalesService())->updateCustomerBalance($request->id);
        } else if ($request->type == 'supplier') {

            (new PurchaseService())->updateSupplierBalance($request->id);

        }
        return back()->withSuccess($message);
    }

    public function approve(DueRequest $request, AccountReview $review)
    {
        $request->approve($review);
        return back()->withSuccess('Transaction Successful!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show(AccountReview $dueCollection)
    {
        $company = auth()->user()->company;
        return view('admin.due.show', compact('dueCollection', 'company'));
    }


    public function edit($id) {
        // $dueCollection = AccountReview::findOrFail($id);
        // $accounts = Account::get();
        // return view('admin.due.supplier-edit', compact('dueCollection', 'accounts'));
    }

    public function destroy(AccountReview $customerCollection)
    {

        dd($customerCollection);
        $customerCollection->delete();

        return back()->withSuccess('Transaction Deleted Successfully');
    }
}
