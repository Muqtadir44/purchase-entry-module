<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Purchase form')]
class PurchaseForm extends Component
{
    public array $rows = [];

    public float $total = 0.0;

    public ?Purchase $purchase = null;

    public bool $isEditing = false;

    public array $items = [];

    public array $brands = [];

    public function mount(?Purchase $purchase = null): void
    {
        $this->purchase = $purchase;
        $this->isEditing = $purchase !== null;

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

        if ($this->isEditing) {
            $this->rows = $this->purchase->items
                ->map(fn (PurchaseItem $purchaseItem): array => [
                    'item_id' => (string) $purchaseItem->item_id,
                    'brand_id' => (string) $purchaseItem->brand_id,
                    'qty' => (int) $purchaseItem->qty,
                    'price' => (float) $purchaseItem->price,
                ])
                ->all();
        }

        if ($this->rows === []) {
            $this->rows = [$this->newRow()];
        }

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

        $this->syncRowsState();
    }

    public function updatedRows(): void
    {
        $this->syncRowsState();
    }

    public function save(): void
    {
        $this->syncRowsState();

        if (! $this->validateDuplicateRows()) {
            throw ValidationException::withMessages([
                'rows' => 'Duplicate item and brand combinations are not allowed.',
            ]);
        }

        $rows = $this->rowsForSaving();

        if ($rows === []) {
            throw ValidationException::withMessages([
                'rows' => 'Add at least one purchase row before saving.',
            ]);
        }

        $validatedRows = Validator::make(['rows' => $rows], $this->purchaseRules())->validate()['rows'];

        DB::transaction(function () use ($validatedRows): void {
            $purchase = $this->purchase ?? new Purchase();
            $purchase->total = $this->total;
            $purchase->save();

            if ($this->isEditing) {
                $purchase->items()->delete();
            }

            foreach ($validatedRows as $row) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->item_id = (int) $row['item_id'];
                $purchaseItem->brand_id = (int) $row['brand_id'];
                $purchaseItem->qty = (int) $row['qty'];
                $purchaseItem->price = (float) $row['price'];
                $purchaseItem->save();
            }

            $this->purchase = $purchase;
        });

        if ($this->isEditing) {
            $this->purchase->load('items');
            $this->rows = $this->purchase->items
                ->map(fn (PurchaseItem $purchaseItem): array => [
                    'item_id' => (string) $purchaseItem->item_id,
                    'brand_id' => (string) $purchaseItem->brand_id,
                    'qty' => (int) $purchaseItem->qty,
                    'price' => (float) $purchaseItem->price,
                ])
                ->all();

            $this->calculateTotal();
        } else {
            $this->resetForm();
        }

        Flux::toast(variant: 'success', text: __($this->isEditing ? 'Purchase updated successfully.' : 'Purchase saved successfully.'));
    }

    public function calculateTotal(): void
    {
        $this->total = round(array_reduce(
            $this->rows,
            static fn (float $carry, array $row): float => $carry + ((float) ($row['qty'] ?? 0) * (float) ($row['price'] ?? 0)),
            0.0,
        ), 2);
    }

    protected function syncRowsState(): void
    {
        $this->calculateTotal();
        $this->validateDuplicateRows();
    }

    protected function rowsForSaving(): array
    {
        return array_values(array_filter($this->rows, fn (array $row): bool => ! $this->isPristineRow($row)));
    }

    protected function purchaseRules(): array
    {
        return [
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'rows.*.brand_id' => ['required', 'integer', 'exists:brands,id'],
            'rows.*.qty' => ['required', 'integer', 'min:1'],
            'rows.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function isPristineRow(array $row): bool
    {
        return ($row['item_id'] ?? '') === ''
            && ($row['brand_id'] ?? '') === ''
            && (int) ($row['qty'] ?? 1) === 1
            && (float) ($row['price'] ?? 0) === 0.0;
    }

    protected function resetForm(): void
    {
        $this->rows = [$this->newRow()];
        $this->total = 0.0;
        $this->resetValidation();
    }

    protected function validateDuplicateRows(): bool
    {
        $this->resetValidation();

        $seen = [];
        $hasDuplicates = false;

        foreach ($this->rows as $index => $row) {
            $itemId = (string) ($row['item_id'] ?? '');
            $brandId = (string) ($row['brand_id'] ?? '');

            if ($itemId === '' || $brandId === '') {
                continue;
            }

            $pairKey = $itemId . '|' . $brandId;

            if (array_key_exists($pairKey, $seen)) {
                $firstIndex = $seen[$pairKey];
                $message = 'This item and brand combination already exists in another row.';

                $this->addError("rows.$firstIndex.item_id", $message);
                $this->addError("rows.$firstIndex.brand_id", $message);
                $this->addError("rows.$index.item_id", $message);
                $this->addError("rows.$index.brand_id", $message);

                $hasDuplicates = true;

                continue;
            }

            $seen[$pairKey] = $index;
        }

        return ! $hasDuplicates;
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
