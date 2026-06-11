<section class="w-full space-y-4">
    <div class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white/80 p-4 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>Purchases</flux:heading>
            <flux:text variant="subtle">Browse saved purchase entries and their totals.</flux:text>
        </div>

        <a href="{{ route('purchases.create') }}" class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200">
            Add Purchase
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Items</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Created</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-neutral-50/70 dark:hover:bg-neutral-800/40">
                            <td class="px-4 py-4 text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                #{{ $purchase->id }}
                            </td>

                            <td class="px-4 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $purchase->items_count }} item(s)
                            </td>

                            <td class="px-4 py-4 text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ number_format($purchase->total, 2) }}
                            </td>

                            <td class="px-4 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $purchase->created_at?->format('d M, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                No purchases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $purchases->links() }}
    </div>
</section>
