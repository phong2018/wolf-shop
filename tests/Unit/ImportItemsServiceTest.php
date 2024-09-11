<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Item;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepository;
use App\Services\Item\ImportItemsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportItemsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up the repository mock
        $this->repository = $this->app->make(ItemRepository::class);
        $this->service = new ImportItemsService($this->repository);
    }

    public function testHandleSuccess()
    {
        // Fake HTTP response
        Http::fake([
            ImportItemsService::URL_GET_LIST_ITEMS => Http::response([
                ['name' => 'Test Item', 'sellIn' => 10, 'quality' => 20],
                ['name' => 'New Item', 'sellIn' => 15, 'quality' => 30],
            ]),
        ]);

        // Execute the service handle method
        $this->service->handle();

        // Assert that items are stored correctly
        $this->assertDatabaseHas('items', ['name' => 'Test Item', 'sell_in' => 10, 'quality' => 20]);
        $this->assertDatabaseHas('items', ['name' => 'New Item', 'sell_in' => 15, 'quality' => 30]);
    }

    public function testProcessItemUpdate()
    {
        // Create an existing item in the database
        $existingItem = Item::factory()->create([
            'name' => 'Existing Item',
            'quality' => 10,
        ]);

        // Call processItem method with data that should update the item
        $this->service->processItem(['name' => 'Existing Item', 'quality' => 20]);

        // Assert the item's quality was updated
        $this->assertDatabaseHas('items', [
            'id' => $existingItem->id,
            'quality' => 20,
        ]);
    }

    public function testProcessItemAddNew()
    {
        // Call processItem method with data for a new item
        $this->service->processItem(['name' => 'New Item', 'sellIn' => 10, 'quality' => 30]);

        // Assert the new item was added
        $this->assertDatabaseHas('items', [
            'name' => 'New Item',
            'sell_in' => 10,
            'quality' => 30,
        ]);
    }
}
