<section class="w-full space-y-4">

    {{-- Header Card --}}
    <div
        class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white/80 p-4 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>Purchase Details</flux:heading>
            <flux:text variant="subtle">View the summary and itemized breakdown of this purchase entry.</flux:text>
        </div>

        <a href="{{ route('purchases.index') }}" wire:navigate
            class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200">
            Back to list
        </a>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Summary Card --}}
        <div class="rounded-xl border border-neutral-200 bg-white/80 p-6 sm:p-8 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 space-y-6 self-start">
            <flux:heading size="lg">Summary</flux:heading>

            <div class="space-y-4">

                <div class="flex justify-between items-center pb-3 border-b border-neutral-100 dark:border-neutral-800 text-sm">
                    <span class="text-neutral-500 dark:text-neutral-400">Date & Time</span>
                    <span class="font-medium text-neutral-950 dark:text-white">{{ $purchase->created_at?->format('d M, Y h:i A') ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-neutral-100 dark:border-neutral-800 text-sm">
                    <span class="text-neutral-500 dark:text-neutral-400">Total Items</span>
                    <span class="font-medium text-neutral-950 dark:text-white">{{ $purchase->items->count() }}</span>
                </div>
                <div class="flex justify-between items-center pt-1 text-sm">
                    <span class="text-neutral-500 dark:text-neutral-400 font-medium">Total Cost</span>
                    <span class="text-xl font-bold text-neutral-950 dark:text-white">${{ number_format($purchase->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="lg:col-span-2 overflow-hidden rounded-xl border border-neutral-200 bg-white/80 p-6 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 space-y-6">
            <flux:heading size="lg">Items</flux:heading>

            <flux:table>
                <flux:table.columns sticky class="bg-neutral-50/80 dark:bg-neutral-800/60">
                    <flux:table.column>Item Name</flux:table.column>
                    <flux:table.column>Brand</flux:table.column>
                    <flux:table.column align="end">Qty</flux:table.column>
                    <flux:table.column align="end">Unit Price</flux:table.column>
                    <flux:table.column align="end">Subtotal</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($purchase->items as $purchaseItem)
                        <flux:table.row key="purchase-item-{{ $purchaseItem->id }}"
                            class="hover:bg-neutral-50/70 dark:hover:bg-neutral-800/40">
                            <flux:table.cell variant="strong">
                                {{ $purchaseItem->item?->name ?? 'N/A' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $purchaseItem->brand?->name ?? 'N/A' }}
                            </flux:table.cell>

                            <flux:table.cell align="end">
                                {{ $purchaseItem->qty }}
                            </flux:table.cell>

                            <flux:table.cell align="end">
                                {{ number_format($purchaseItem->price, 2) }}
                            </flux:table.cell>

                            <flux:table.cell align="end" variant="strong">
                                {{ number_format($purchaseItem->qty * $purchaseItem->price, 2) }}
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row key="items-empty">
                            <flux:table.cell colspan="5"
                                class="py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                No items found in this purchase.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

</section>
