<?php

namespace App\Http\Controllers;

use App\Account;
use App\FundTransfer;
use App\Traits\FileSaver;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class FundTransferController extends Controller
{
    use FileSaver;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accounts = Account::companies()->pluck('account_name', 'id');

        $fundTransfers = FundTransfer::companies()->orderBy('date')
        ->when ($request->filled('account_id'), function ($q) use ($request) {
            $q->where(function ($qr) use ($request) {
                $qr->where('fk_from_account_id', $request->account_id)
                    ->orWhere('fk_to_account_id', $request->account_id);
            });
        })
        ->when($request->filled('from'), function ($q) use ($request) {
            $q->where('date', '>=', $request->from);
        })
        ->when($request->filled('to'), function ($q) use ($request) {
            $q->where('date', '<=', $request->to);
        })->get() ?? collect([]);

        if (!count($request->all())) {
            $fundTransfers = collect([]);
        }

        return view('admin.account.fund-transfers.index', compact('fundTransfers', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::companies()->withCount(['transactions as total_amount' => function ($q) {
            $q->select(DB::Raw('SUM(amount)'));
        }])->get();
        return view('admin.account.fund-transfers.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_account_id'   => 'required',
            'to_account_id'     => 'required|different:from_account_id',
            'amount'            => 'required|numeric|between:0,999999999999.99',
            'image'             => 'nullable|image'
        ]);

        $auth_user = auth()->user();

        $data = [
            'fk_company_id'         => $auth_user->fk_company_id,
            'fk_created_by'         => $auth_user->id,
            'fk_from_account_id'    => $request->from_account_id,
            'fk_to_account_id'      => $request->to_account_id,
            'date'                  => date('Y-m-d'),
            'amount'                => $request->amount,
            'comment'               => $request->comment,
            'reference_no'          => $request->reference_no
        ];

        try {
            DB::transaction(function () use ($data, $request) {
                $created = FundTransfer::create($data);
                $this->upload_file($request->image, $created, 'image', 'fund-transfer');
                $this->payment($created);
            });

            return redirect()->route('fund-transfers.index')->withMessage('Fund transfer success')->withSuccess('Fund transfer created successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something error, please check']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $fundTransfer = FundTransfer::find($id);
            // <!-- delete image if exist -->
            if (file_exists($fundTransfer->image)) {
                unlink($fundTransfer->image);
            }

            Transaction::where('transactionable_id', $id)->where('transactionable_type', 'App\FundTransfer')->delete();
            FundTransfer::destroy($id);

            return redirect()->back()->withSuccess('Fund transfer deleted success');
        } catch (\Exception $ex) {
            return redirect()->back()->withError('Something error, please check');
        }
    }

    public function payment($fundTransfer)
    {
        $transaction = $fundTransfer->transaction()->create([
            'fk_account_id' => $fundTransfer->fk_from_account_id,
            'date'          => $fundTransfer->date,
            'amount'        => -$fundTransfer->amount
        ]);

        $transaction2 = $fundTransfer->transaction()->create([
            'fk_account_id' => $fundTransfer->fk_to_account_id,
            'date'          => $fundTransfer->date,
            'amount'        => $fundTransfer->amount
        ]);
    }
}
