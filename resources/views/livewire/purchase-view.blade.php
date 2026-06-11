<section class="w-full space-y-4">

    {{-- Header Card --}}
    <div
        class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white/80 p-4 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>View Purchases</flux:heading>
            <flux:text variant="subtle">Browse saved purchase entries and their totals.</flux:text>
        </div>

        <a href="{{ route('purchases.index') }}" wire:navigate
            class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200">
            Back to list
        </a>
    </div>

    {{-- Table Card --}}
    <div
        class="overflow-hidden rounded-xl border border-neutral-200 bg-white/80 shadow-sm backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/70 p-4">



    </div>

</section>
