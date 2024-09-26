<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use WolfShop\Item as WolfShopItem;

class ProcessItemChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $items;

    /**
     * Create a new job instance.
     *
     * @param array $items
     * @return void
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Map items to the appropriate structure for upsert
            $itemsToUpsert = array_map(function ($item) {
                $newItem = new WolfShopItem(
                    $item['name'] ?? '',
                    $item['sellIn'] ?? 0,
                    $item['quality'] ?? 0
                );

                return [
                    'name' => $newItem->name,
                    'sell_in' => $newItem->sellIn,
                    'quality' => $newItem->quality,
                    'data' => json_encode($item['data'] ?? []),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $this->items);
            
            Item::upsert(
                $itemsToUpsert, // Data to insert or update
                ['name'], // Columns to match for uniqueness
                ['sell_in', 'quality', 'data', 'updated_at'] // Columns to update if match is found
            );
        } catch (\Exception $e) {
            Log::error("Failed to upsert items in chunk: " . $e->getMessage());
        }
    }
}
