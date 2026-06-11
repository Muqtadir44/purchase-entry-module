<?php

namespace App\Livewire;

use Livewire\Component;

class PurchaseForm extends Component
{
    public $rows = [];
    public $total = 0;

    public function mount()
    {
        
    }

    public function render()
    {
        return view('livewire.purchase-form');
    }
}
