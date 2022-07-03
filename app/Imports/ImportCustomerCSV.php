<?php

namespace App\Imports;

use App\CustomerUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCustomerCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new CustomerUpload([
            'name'              => $row['name'],
            'email'             => $row['email'],
            'phone'             => $row['phone'],
            'address'           => $row['address'],
            'customer_category' => $row['customer_category'],
            'opening_due'       => $row['opening_due'],
            'due_limit'         => $row['due_limit']
        ]);
    }
}
