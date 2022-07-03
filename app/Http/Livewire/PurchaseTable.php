<?php

namespace App\Http\Livewire;

use App\Purchase;

class PurchaseTable
{

    public $search = '';
    public $perPage = 15;
    public $sortField = 'id';
    public $sortAsc = false;

    public function sortBy($field)
    {
        if ($this->sortField === $field){
            $this->sortAsc = !$this->sortAsc;
        }
        else{
            $this->sortAsc = true;
        }
        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.purchase-table', [
            'purchases' => Purchase::search($this->search)
                ->where('fk_company_id', companyId())
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),

            'sortField' => $this->sortField,
            'sortAsc' => $this->sortAsc,
        ]);
    }
}
