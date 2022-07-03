<?php

namespace App\Http\Requests;

use App\Voucher;
use App\VoucherAccountChart;
use App\VoucherChartPayment;
use App\VoucherPayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fk_party_id' => 'required',
            'voucher_date'  =>'required|max:160',
            'payable_amount' => 'required',
            'paid_amount' => 'required',
            'fk_account_id' => 'required',
            'fk_payment_id' => 'required',
            'fk_account_chart_id.*' => 'required',
            'payable_amount_unit.*' => 'required',
            'paid_amount_unit.*' => 'required'
        ];
    }



    private function invoice() : Model
    {
        $data = $this->only(
            'fk_party_id', 'voucher_date', 'voucher_reference', 'payable_amount', 'paid_amount', 'due_amount', 'cheque_number'
        );
        $data['voucher_type'] = $this->type;

        return Voucher::create($data);
    }

    public function persist()
    {
        return DB::transaction(function (){

            /** @var Voucher $voucher */
            $voucher = $this->invoice();
            $voucherPayment = $this->paymentInfo($voucher);

            $this->chartTransaction($voucher);

            return $voucher;
        });

    }


    private function paymentInfo(Voucher $voucher) : Model
    {
        return $voucher->voucher_payment()->create(
            $this->only('fk_payment_id', 'fk_account_id')
        );
    }

    private function chartTransaction(Voucher $voucher)
    {
        foreach ($this->fk_account_chart_id as $key => $chartId) {
            $chart = $this->chart($voucher, $key);
            if ($this->paid_amount_unit[$key]) {
                $this->chartPayment($voucher, $chart, $key);
            }
        }
    }

    private function chart(Voucher $voucher, $key) : Model
    {
        return $voucher->voucher_account_charts()->create([
            'fk_account_chart_id' => $this->fk_account_chart_id[$key],
            'payable_amount' => $this->payable_amount_unit[$key],
            'paid_amount' => $this->paid_amount_unit[$key],
            'description' => $this->description[$key],
        ]);
    }

    private function chartPayment(Voucher $voucher, VoucherAccountChart $chart, $key)
    {
        $chartPayment = $voucher->voucher_chart_payments()->create([
            'fk_voucher_account_chart_id' => $chart->id,
            'fk_voucher_payment_id' => $voucher->voucher_payment->id,
        ]);


        if($this->paid_amount_unit[$key] > 0){
            $this->payment($chartPayment, $key);
        }


    }

    public function payment(VoucherChartPayment $chartPayment, $key)
    {
        $amount = $this->type == 'debit'
            ? -$this->paid_amount_unit[$key]
            : $this->paid_amount_unit[$key];

        // hit on transaction table
        $chartPayment->payments()->create([
            'fk_account_id' => $this->fk_account_id,
            'amount' => $amount,
            'date' => $this->voucher_date,
        ]);
    }


}
