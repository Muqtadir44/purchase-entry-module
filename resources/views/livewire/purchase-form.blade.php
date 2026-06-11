<section class="w-full space-y-4">
    <div
        class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white/80 p-4 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>{{ $isEditing ? 'Edit Purchase' : 'Add Purchase' }}</flux:heading>
            <flux:text variant="subtle">{{ $isEditing ? 'Update purchase details and save changes.' : 'Enter details for the new purchase.' }}</flux:text>
        </div>

        <a href="{{ route('purchases.index') }}" wire:navigate
            class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200">
            Back to List
        </a>
    </div>

    <div x-data="{ total: @entangle('total').live }">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="relative w-full overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
                <table class="min-w-full w-full table-fixed divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead>
                        <tr>
                            <th
                                class="w-[30%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">
                                Item</th>
                            <th
                                class="w-[30%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">
                                Brand</th>
                            <th
                                class="w-[14%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">
                                Qty</th>
                            <th
                                class="w-[16%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">
                                Price</th>
                            <th
                                class="w-[10%] px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach ($rows as $index => $row)
                            <tr wire:key="purchase-row-{{ $index }}">
                                <td class="px-4 py-3">
                                    <flux:select class="w-full" wire:model="rows.{{ $index }}.item_id">
                                        <flux:select.option value="">Select item</flux:select.option>
                                        @foreach ($items as $item)
                                            <flux:select.option value="{{ $item['id'] }}">{{ $item['name'] }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    @error('rows.' . $index . '.item_id')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="px-4 py-3">
                                    <flux:select class="w-full" wire:model="rows.{{ $index }}.brand_id">
                                        <flux:select.option value="">Select brand</flux:select.option>
                                        @foreach ($brands as $brand)
                                            <flux:select.option value="{{ $brand['id'] }}">{{ $brand['name'] }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    @error('rows.' . $index . '.brand_id')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="px-4 py-3">
                                    <flux:input class="w-full" type="number" min="1" step="1"
                                        wire:model.live="rows.{{ $index }}.qty" />
                                </td>

                                <td class="px-4 py-3">
                                    <flux:input class="w-full" type="number" min="0" step="0.01"
                                        wire:model.live="rows.{{ $index }}.price" />
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <flux:button type="button" variant="danger" class="w-full sm:w-auto"
                                        wire:click="removeRow({{ $index }})">
                                        Remove
                                    </flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <td colspan="3" class="px-4 py-3">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <flux:button type="button" variant="primary" class="w-full sm:w-auto"
                                        wire:click="addRow">
                                        Add Row
                                    </flux:button>

                                    <flux:button type="submit" variant="primary" class="w-full sm:w-auto">
                                        Save Purchase
                                    </flux:button>
                                </div>

                                @error('rows')
                                    <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </td>
                            <td
                                class="px-4 py-3 text-right text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                Total</td>
                            <td
                                class="px-4 py-3 text-right text-base font-semibold text-neutral-900 dark:text-neutral-100">
                                <span x-text="Number(total).toFixed(2)">{{ number_format($total, 2) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</section>
