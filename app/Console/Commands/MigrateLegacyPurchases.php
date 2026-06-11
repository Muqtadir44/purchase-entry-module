<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLegacyPurchases extends Command
{
    protected $signature = 'legacy:migrate-purchases';

    protected $description = 'Migrate legacy purchase records into normalized tables';

    protected array $legacyPurchases = [
        [
            'item_name' => 'Sugar',
            'brand_name' => 'ABC',
            'qty' => 10,
            'price' => 100,
        ],
    ];

    public function handle(): int
    {
        $created = 0;
        $skipped = 0;

        DB::transaction(function () use (&$created, &$skipped) {

            foreach ($this->legacyPurchases as $legacyPurchase) {

                if (
                    empty($legacyPurchase['item_name']) ||
                    empty($legacyPurchase['brand_name']) ||
                    !isset($legacyPurchase['qty']) ||
                    !isset($legacyPurchase['price'])
                ) {
                    $this->warn('Skipping invalid legacy record.');
                    continue;
                }

                $itemName = trim($legacyPurchase['item_name']);
                $brandName = trim($legacyPurchase['brand_name']);
                $qty = (int) $legacyPurchase['qty'];
                $price = (float) $legacyPurchase['price'];

                $total = $qty * $price;

                $item = Item::firstOrCreate([
                    'name' => $itemName,
                ]);

                $brand = Brand::firstOrCreate([
                    'name' => $brandName,
                ]);

                // Idempotency check
                $alreadyMigrated = PurchaseItem::query()
                    ->where('item_id', $item->id)
                    ->where('brand_id', $brand->id)
                    ->where('qty', $qty)
                    ->where('price', $price)
                    ->whereHas('purchase', function ($query) use ($total) {
                        $query->where('total', $total);
                    })
                    ->exists();

                if ($alreadyMigrated) {
                    $skipped++;
                    continue;
                }

                $purchase = Purchase::create([
                    'total' => $total,
                ]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item->id,
                    'brand_id' => $brand->id,
                    'qty' => $qty,
                    'price' => $price,
                ]);

                $created++;
            }
        });

        $this->info(
            "Migration completed. Created: {$created}, Skipped: {$skipped}"
        );

        return self::SUCCESS;
    }
}
