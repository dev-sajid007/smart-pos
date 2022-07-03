<?php

namespace App\Http\Requests;

use App\AccountReview;
use App\Customer;
use App\Services\PurchaseService;
use App\Services\SalesService;
use App\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class DueRequest extends FormRequest
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
        $rules = [
            'amount' => 'required|gt:0',
            'id' => 'required',
            'account_id' => 'required'
        ];
        switch ($this->method()){
            case 'POST':
                return $rules;
                break;
            case 'PATCH':
                return [];
        }

    }

    protected $_amount = 0;

    public function persist()
    {
        return DB::transaction(function () {

            $user = $this->transactionFor($this->id);  // get the customer details

            $previous_due = '';
            $current_due = '';
            $type = 'advance';

            if ($this->type == 'supplier') {
                $previous_due = $this->payable_dueAmount - $this->advanced_payment;
                $current_due = $this->current_due;
            } else if($this->type == 'customer') {
                $previous_due = $this->previous_balance - $this->advanced_payment;
                $current_due = $previous_due - $this->amount;
            }


            $review = $user->accountReviews()->create([
                'type'              => $type,
                'amount'            => $this->_amount,
                'fk_created_by'     => auth()->id(),
                'account_id'        => $this->account_id,
                'previous_due'      => $previous_due,
                'current_due'       => $current_due,
                'discount'          => $this->discount ?? 0,
                'date'              => $this->date,
                'note'              => $this->note,
                'payment_method_id' => $this->payment_method_id,
                'status'            => 1,   // for no need approve
            ]);

            if ($this->type == 'customer') {
                $this->approve($review);

            } else {
                (new PurchaseService())->updateSupplierBalance($this->id);
            }

        });
    }

    public function approve($review)
    {
        return DB::transaction(function ()use($review){

            /** @var AccountReview $review */
            $transaction = $review->transaction()->create([
                'fk_account_id' => $review->account_id,
                'date' => $review->date,
                'amount' => $review->amount
            ]);


            $review->update([
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
            if($review->transactionable_type == 'App\Supplier') {
                (new PurchaseService())->updateSupplierBalance($review->transactionable_id);
            } else {
                // update customer balance
                (new SalesService())->updateCustomerBalance($review->transactionable_id);
            }
        });
    }



    private function transactionFor($id)
    {
        switch ($this->type){
            case 'customer':

                $this->_amount = $this->payment_type == 'due'
                    ? -$this->amount
                    : $this->amount;

                return Customer::find($id);
                break;

            case 'supplier':

                $this->_amount = $this->payment_type == 'due'
                    ? $this->amount
                    : -$this->amount;

                return Supplier::find($id);
                break;

        }
    }

}
