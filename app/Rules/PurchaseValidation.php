<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PurchaseValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $flag = false;
        foreach ($value as $qty){
            if ($qty == 0) continue;
            else{
                $flag = true;
            }
        }
        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'All Product Quantity can not be 0';
    }
}
