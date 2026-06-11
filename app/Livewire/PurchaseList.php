<?php

namespace App\Livewire;

use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;

    public function render()
    {
        $purchases = Purchase::query()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('livewire.purchase-list', [
            'purchases' => $purchases,
        ]);
    }
}
