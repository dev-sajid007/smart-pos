<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherRequest;
use Illuminate\Http\Request;
use App\Account;
use App\Payment;
use App\Party;
use App\Voucher;
use App\AccountChart;


class MakePaymentController extends Controller
{
    public $type;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->type = $request->type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vouchers = Voucher::companies()->userLog()
            ->where('voucher_type', $this->type)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('voucher_date', "%{$request->search}%")
                ->orWhereHas('party', function ($qr) use ($request) {
                    $qr->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%");
                });
        })
    ->orderByDesc('id')->paginate(30);

        return view('admin.payments.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::companies()
                    ->withCount(['transactions AS total_amount' => function ($query) {
                        return $query->select(\DB::raw("SUM(amount) as amount"));
                    }])
                    ->get(['account_name', 'id']);
        $account_charts = AccountChart::companies()->get();
        $payment_methods = Payment::companies()->where('status', 1)->get(['method_name', 'id']);
        $parties = Party::companies()->get();

         return view('admin.payments.create', compact(
             'accounts', 'account_charts', 'payment_methods', 'parties'
         ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
         $voucher  = $request->persist();

        return redirect()->route('payments.show', [
            'payment' => $voucher,
            'type' => $this->type
        ])->withSuccess(ucfirst($this->type).' Voucher Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MakePayment  $makePayment
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $payment)
    {
        $voucher = $payment;

        $company = auth()->user()->company;

        return view('admin.payments.show', compact('voucher', 'company'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Voucher $payment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Voucher $payment)
    {
        try {
            $payment->delete();
            return redirect()->back()->withSuccess(ucfirst($this->type). " Voucher Deleted!");
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
