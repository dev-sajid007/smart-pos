<?php

namespace App\Http\Requests;

use App\Rules\PurchasePaidAmountValidation;
use App\Services\PurchaseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PurchaseRequest extends FormRequest
{
    private $service;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
    }
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
            'purchase_date'     => 'required|max:160|date',
            'fk_supplier_id'    => 'required',
            'product_ids.*'     => 'required|not_in:0',
            'unit_prices.*'     => 'required',
            'sub_total.*'       => 'required',
            'account_id'        => 'required',
            'total_paid_amount' => new PurchasePaidAmountValidation(),
        ];

        switch ($this->method()){
            case 'PATCH':
                return [];
                break;
            case 'POST':
                return $rules;
                break;
        }
    }

    public function messages()
    {
        return [
            'product_ids.*.required'    => 'Select Product',
            'product_ids.*.not_in'      => 'The selected product is invalid',
            'unit_prices.*.required'    => 'The unit price is required',
            'quantities.*.required'     => 'The quantity field is required'
        ];
    }




    public function storePurchase()
    {
        return DB::transaction(function () {

            // store purchase invoice to purchases table
            $this->service->makeInvoice();

            // store purchase details
            $this->service->storePurchaseDetails();
            // dd(1);

            $this->service->approvePurchase();


            (new PurchaseService())->updateSupplierBalance($this->fk_supplier_id);

            return $this->service->purchase;
        });
    }
}
