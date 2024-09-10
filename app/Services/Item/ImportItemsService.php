<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WolfShop\Item as WolfShopItem;

class ImportItemsService extends BaseService
{
    public const URL_GET_LIST_ITEMS = 'https://api.restful-api.dev/objects';

    protected $collectsData = true;

    public function __construct(
        ItemRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     * @return mixed
     */
    public function handle()
    {
        $response = Http::get(self::URL_GET_LIST_ITEMS);

        if ($response->ok()) {
            $items = $response->json();
            foreach ($items as $item) {
                if (isset($item['name']) && ! empty($item['name'])) {
                    $this->processItem($item);
                } else {
                    Log::warning('Item missing name, skipping item: ' . json_encode($item));
                }
            }
        } else {
            Log::error('Failed to fetch data from API.');
        }
    }

    /**
     * process Item
     *
     * @param mixed[] $item The item data from the API.
     * @return mixed
     */
    private function processItem(array $item): void
    {
        $existingItem = $this->repository->findByField('name', $item['name'])->first();

        if ($existingItem) {
            $this->updateQuality($existingItem, $item);
        } else {
            $this->addNewItem($item);
        }
    }

    /**
     * Updates the quality of an existing item using the data retrieved from the API.
     *
     * @param mixed $existingItem The existing item model instance.
     * @param mixed[] $item The item data from the API.
     * @return mixed
     */
    private function updateQuality($existingItem, array $item)
    {
        $this->repository->update([
            'quality' => $item['quality'] ?? $existingItem->quality,
        ], $existingItem->id);
    }

    /**
     * Adds a new item to the repository using the data from the API.
     *
     * @param mixed[] $item The item data from the API.
     * @return mixed
     */
    private function addNewItem(array $item)
    {
        $newItem = new WolfShopItem(
            $item['name'] ?? '',
            $item['sellIn'] ?? 0,
            $item['quality'] ?? 0
        );

        $itemModel = new Item([
            'name' => $newItem->name,
            'sell_in' => $newItem->sellIn,
            'quality' => $newItem->quality,
            'data' => json_encode($item['data'] ?? []),
        ]);

        try {
            $this->repository->create($itemModel->toArray());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
