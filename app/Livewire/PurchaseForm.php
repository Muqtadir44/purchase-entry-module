<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Item;
use Livewire\Component;

class PurchaseForm extends Component
{
    public array $rows = [];

    public float $total = 0.0;

    public array $items = [];

    public array $brands = [];

    public function mount(): void
    {
        $this->items = Item::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->toArray();

        $this->brands = Brand::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->toArray();

        $this->rows = [$this->newRow()];

        $this->calculateTotal();
    }

    public function addRow(): void
    {
        $this->rows[] = $this->newRow();
    }

    public function removeRow(int $index): void
    {
        unset($this->rows[$index]);

        $this->rows = array_values($this->rows);

        if ($this->rows === []) {
            $this->rows[] = $this->newRow();
        }

        $this->calculateTotal();
    }

    public function updatedRows(): void
    {
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total = round(array_reduce(
            $this->rows,
            static fn (float $carry, array $row): float => $carry + ((float) ($row['qty'] ?? 0) * (float) ($row['price'] ?? 0)),
            0.0,
        ), 2);
    }

    protected function newRow(): array
    {
        return [
            'item_id' => '',
            'brand_id' => '',
            'qty' => 1,
            'price' => 0,
        ];
    }

    public function render()
    {
        return view('livewire.purchase-form');
    }
}
