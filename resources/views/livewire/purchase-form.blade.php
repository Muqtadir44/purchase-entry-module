<div>
    <div class="relative w-full overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
        <table class="min-w-full w-full table-fixed divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead>
                <tr>
                    <th class="w-[30%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">Item</th>
                    <th class="w-[30%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">Brand</th>
                    <th class="w-[14%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">Qty</th>
                    <th class="w-[16%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">Price</th>
                    <th class="w-[10%] px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:text-neutral-200">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach ($rows as $index => $row)
                    <tr wire:key="purchase-row-{{ $index }}">
                        <td class="px-4 py-3">
                            <flux:select class="w-full" wire:model="rows.{{ $index }}.item_id">
                                <flux:select.option value="">Select item</flux:select.option>
                                @foreach ($items as $item)
                                    <flux:select.option value="{{ $item['id'] }}">{{ $item['name'] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </td>

                        <td class="px-4 py-3">
                            <flux:select class="w-full" wire:model="rows.{{ $index }}.brand_id">
                                <flux:select.option value="">Select brand</flux:select.option>
                                @foreach ($brands as $brand)
                                    <flux:select.option value="{{ $brand['id'] }}">{{ $brand['name'] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </td>

                        <td class="px-4 py-3">
                            <flux:input
                                class="w-full"
                                type="number"
                                min="1"
                                step="1"
                                wire:model.live="rows.{{ $index }}.qty"
                            />
                        </td>

                        <td class="px-4 py-3">
                            <flux:input
                                class="w-full"
                                type="number"
                                min="0"
                                step="0.01"
                                wire:model.live="rows.{{ $index }}.price"
                            />
                        </td>

                        <td class="px-4 py-3 text-right">
                            <flux:button
                                type="button"
                                variant="danger"
                                class="w-full sm:w-auto"
                                wire:click="removeRow({{ $index }})"
                            >
                                Remove
                            </flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
                <tr>
                    <td colspan="3" class="px-4 py-3">
                        <flux:button
                            type="button"
                            variant="primary"
                            class="w-full sm:w-auto"
                            wire:click="addRow"
                        >
                            Add Row
                        </flux:button>
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-neutral-700 dark:text-neutral-300">Total</td>
                    <td class="px-4 py-3 text-right text-base font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ number_format($total, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
