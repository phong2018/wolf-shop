<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WolfShop\Item;

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
                $existingItem = $this->repository->findByField('name', $item['name']);

                if ($existingItem) {
                    $this->repository->update([
                        'quality' => $item['quality'],
                    ], $existingItem->id);
                } else {
                    $newItem = new Item(
                        $item['name'],
                        $item['sellIn'],
                        $item['quality']
                    );
                }
            }
        } else {
            Log::error('Failed to fetch data from API.');
        }
    }
}
