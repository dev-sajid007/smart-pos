<?php

namespace App\Http\Requests;

use App\Models\Inventory\ProductDamage;
use App\Product;
use function foo\func;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ProductDamageRequest extends FormRequest
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
            'fk_product_id.*' => 'distinct',
            'quantity.*' => 'lte:available_qty',
            'reference.*' => 'required'
        ];
    }

    public function persist()
    {

        return DB::transaction(function () {
            $invoice = ProductDamage::create([
                'date'      => $this->date,
                'reference' => $this->reference,
                'amount'    => 0,
            ]);

            $this->makeWastage($invoice);
            $this->updateInvoiceTotal($invoice);

        });
    }

    private function updateInvoiceTotal($invoice)
    {
        $totalAmount = $invoice->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        $invoice->update(['amount' => $totalAmount]);
    }

    private function makeWastage(ProductDamage $invoice)
    {
        foreach ($this->product_id as $key => $product_id){
            $damagedItem = $invoice->items()->create([
                'fk_product_id' => $product_id,
                'description'   => $this->description[$key],
                'type'          => $this->type[$key],
                'quantity'      => $this->quantity[$key],
                'price'         => Product::find($product_id)->product_cost
            ]);

            $wastage = $damagedItem->wastage()->create([
                'wastagable_type'   => ProductDamage::class,
                'wastagable_id'     => $invoice->id,
                'quantity'          => $this->quantity[$key]
            ]);
            $wastage->increaseWastageStock();

        }
    }
}
