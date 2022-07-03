<?php

namespace App\Imports;

use App\SupplierUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportSupplierCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new SupplierUpload([
            'name'              => $row['name'],
            'email'             => $row['email'],
            'phone'             => $row['phone'],
            'address'           => $row['address'],
            'opening_due'       => $row['opening_due']
        ]);
    }
}
