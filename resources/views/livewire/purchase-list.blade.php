<section class="w-full space-y-4">

    {{-- Header Card --}}
    <div
        class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white/80 p-4 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>Purchases</flux:heading>
            <flux:text variant="subtle">Browse saved purchase entries and their totals.</flux:text>
        </div>

        @role('Admin')
        <a href="{{ route('purchases.create') }}" wire:navigate
            class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200">
            Add Purchase
        </a>
        @endrole
    </div>

    {{-- Table Card --}}
    <div
        class="overflow-hidden rounded-xl border border-neutral-200 bg-white/80 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 p-4">

        <flux:table :paginate="$purchases">

            <flux:table.columns sticky class="bg-neutral-50/80 dark:bg-neutral-800/60">
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Items</flux:table.column>
                <flux:table.column align="end">Total</flux:table.column>
                <flux:table.column>Created</flux:table.column>
                <flux:table.column align="center">Action</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($purchases as $purchase)
                    <flux:table.row key="purchase-{{ $purchase->id }}"
                        class="hover:bg-neutral-50/70 dark:hover:bg-neutral-800/40">

                        <flux:table.cell variant="strong">
                            {{ $loop->iteration }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $purchase->items_count }} item(s)
                        </flux:table.cell>

                        <flux:table.cell align="end" variant="strong">
                            {{ number_format($purchase->total, 2) }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $purchase->created_at?->format('d M, Y h:i A') }}
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <flux:dropdown>
                                <flux:button icon="ellipsis-vertical" size="sm" variant="ghost" />

                                <flux:menu>
                                    <flux:menu.item icon="eye" :href="route('purchases.view', $purchase)" wire:navigate>
                                        View
                                    </flux:menu.item>
                                    @role('Admin')
                                    <flux:menu.item icon="pencil-square" :href="route('purchases.edit', $purchase)"
                                        wire:navigate>
                                        Edit
                                    </flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $purchase->id }})">Delete</flux:menu.item>
                                    @endrole
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row key="purchases-empty">
                        <flux:table.cell colspan="5"
                            class="py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            No purchases found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>

        </flux:table>

    </div>

    <flux:modal name="delete-purchase-modal" class="max-w-md md:min-w-md" @close="$wire.closeDeleteModal()"
        wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="space-y-2">
                <flux:heading size="lg">{{ __('Delete purchase') }}</flux:heading>
                <flux:text>
                    {{ __('Are you sure you want to delete this purchase? This action cannot be undone and all associated items will be permanently removed.') }}
                </flux:text>
            </div>

            <div class="flex gap-3 justify-end">
                <flux:modal.close>
                    <flux:button variant="outline">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deletePurchase">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>

</section>