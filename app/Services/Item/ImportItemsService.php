<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Models\Item;
use App\Jobs\ProcessItemChunk;
use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WolfShop\Item as WolfShopItem;

class ImportItemsService extends BaseService
{
    public const URL_GET_LIST_ITEMS = 'https://api.restful-api.dev/objects';

    protected $collectsData = true;
    protected $chunkSize = 500; 

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Main logic to handle the data.
     * @return void
     */
    public function handle(): void
    {
        $response = Http::get(self::URL_GET_LIST_ITEMS);

        if ($response->ok()) {
            $items = $response->json();

            // Process items in chunks and dispatch jobs
            collect($items)
                ->chunk($this->chunkSize)
                ->each(function ($chunk) {
                    ProcessItemChunk::dispatch($chunk->toArray());
                });
        } else {
            Log::error('Failed to fetch data from API.');
        }
    }
}
