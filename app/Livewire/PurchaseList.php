<?php

namespace App\Livewire;

use App\Models\Purchase;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;

    public ?int $deletingPurchaseId = null;

    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        $this->deletingPurchaseId = $id;
        $this->showDeleteModal = true;
    }

    public function deletePurchase(): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        if (! $this->deletingPurchaseId) {
            return;
        }

        $purchase = Purchase::find($this->deletingPurchaseId);
        
        if ($purchase) {
            $purchase->delete();

            Flux::toast(
                variant: 'success',
                text: __('Purchase deleted successfully.')
            );
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingPurchaseId = null;
    }

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
