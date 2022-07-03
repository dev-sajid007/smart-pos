<?php

namespace App\Http\Requests;

use App\CustomerPricing;
use App\Http\Controllers\SaleController;
use App\Product;
use App\ProductStock;
use App\PurchaseDetails;
use App\Sale;
use App\SaleDistributor;
use App\SalesDetails;
use App\Services\SalesService;
use App\SoftwareSetting;
use App\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    private $service;

    public function __construct(SalesService $salesService)
    {
        $this->service = $salesService;
    }

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
            'product_ids.*'     => 'required|not_in:0',//|distinct
            'unit_prices.*'     => 'required',
            'fk_customer_id'    => 'required',
            'quantities.*'      => 'required',
            'invoice_total'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_ids.*.required'    => 'The selected product is invalid',
            'product_ids.*.not_in'      => 'The selected product is invalid',
            'unit_prices.*.required'    => 'The unit price is required',
            'quantities.*.required'     => 'The quantity field is required',
            'quantities.*.lte'          => 'Stock Unavailable!',
        ];
    }




    public function storeSale()
    {
        DB::transaction(function () {

            // store sale on sales table
            $this->service->createInvoice();

            // add currier for sale
            $this->service->addSaleCurrier();

            // add meta sale where include receiver info
            $this->service->addMetaSale();

            // store product items to sales_details table
            $this->service->storeSaleDetails();

            // store customer payment
            $this->service->makeCustomerPayment();

            // update total sales amount
            $this->service->updateSalesTotalAmount();

            // update customer balance
            $this->service->updateCustomerBalance($this->fk_customer_id);

            // Add marketers Commission
            $this->service->marketers_commission();
        });


        return $this->service->sale;
    }
}
