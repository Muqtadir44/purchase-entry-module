<?php

namespace App\Livewire;

use Livewire\Component;

class PurchaseForm extends Component
{
    public $rows = [];
    public $total = 0;

    public function mount()
    {
        $this->rows = [
            [
                'item_id' => '',
                'brand_id' => '',
                'qty' => 1,
                'price' => 0,
            ]
        ];
    }

    public function render()
    {
        return view('livewire.purchase-form');
    }
}
