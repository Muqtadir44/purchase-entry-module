<?php

namespace App\Livewire;

use App\Models\Purchase;
use Livewire\Component;

class PurchaseView extends Component
{
    public ?Purchase $purchase = null;
    public function mount(?Purchase $purchase = null): void
    {
        $this->purchase = $purchase;

        if ($this->purchase) {
            $this->purchase->load(['items.item', 'items.brand']);
        }
    }
    public function render()
    {
        return view('livewire.purchase-view');
    }
}
