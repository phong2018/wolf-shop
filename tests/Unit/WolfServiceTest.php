<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use WolfShop\Item;
use WolfShop\WolfService;

class WolfServiceTest extends TestCase
{
    /**
     * testUpdateQualityForRegularItemBeforeSellIn:
     * Validates that a regular item’s quality decreases by 1 before the sell-by date.
     */
    public function testUpdateQualityForRegularItemBeforeSellIn(): void
    {
        $item = new Item('Regular Item', 10, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(19, $item->quality); // Quality should decrease by 1
        $this->assertEquals(9, $item->sellIn);
    }

    /**
     * testUpdateQualityForRegularItemAfterSellIn:
     * Checks that a regular item’s quality degrades twice as fast after the sell-by date.
     */
    public function testUpdateQualityForRegularItemAfterSellIn(): void
    {
        $item = new Item('Regular Item', 0, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(18, $item->quality); // Quality should degrade twice as fast
        $this->assertEquals(-1, $item->sellIn);
    }

    /**
     * testUpdateQualityForAppleAirPods:
     * Confirms that "Apple AirPods" increases in quality by 1 as it ages.
     */
    public function testUpdateQualityForAppleAirPods(): void
    {
        $item = new Item('Apple AirPods', 10, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(21, $item->quality); // Quality should increase by 1
        $this->assertEquals(9, $item->sellIn);
    }

    /**
     * testUpdateQualityForAppleIPadAirWithDifferentSellInValues:
     * Tests the "Apple iPad Air" for different sell-by dates and ensures quality increases appropriately or drops to 0 after the sell-by date.
     */
    public function testUpdateQualityForAppleIPadAirWithDifferentSellInValues(): void
    {
        $item = new Item('Apple iPad Air', 15, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();
        $this->assertEquals(21, $item->quality); // Quality should increase by 1

        $item->sellIn = 10;
        $service->updateQuality();
        $this->assertEquals(23, $item->quality); // Quality should increase by 2

        $item->sellIn = 5;
        $service->updateQuality();
        $this->assertEquals(26, $item->quality); // Quality should increase by 3

        $item->sellIn = 0;
        $service->updateQuality();
        $this->assertEquals(0, $item->quality); // Quality drops to 0 after sell-by date
    }

    /**
     * testUpdateQualityForSamsungGalaxyS23:
     * Ensures that "Samsung Galaxy S23" maintains its quality and does not change.
     */
    public function testUpdateQualityForSamsungGalaxyS23(): void
    {
        $item = new Item('Samsung Galaxy S23', 10, 80);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(80, $item->quality); // Quality remains the same
        $this->assertEquals(10, $item->sellIn); // TODO: check again 9
    }

    /**
     * testUpdateQualityForXiaomiRedmiNote13BeforeSellIn:
     * Validates that "Xiaomi Redmi Note 13" degrades twice as fast before the sell-by date.
     */
    public function testUpdateQualityForXiaomiRedmiNote13BeforeSellIn(): void
    {
        $item = new Item('Xiaomi Redmi Note 13', 10, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(18, $item->quality); // Degrades twice as fast
        $this->assertEquals(9, $item->sellIn);
    }

    /**
     * testUpdateQualityForXiaomiRedmiNote13AfterSellIn:
     * Ensures that "Xiaomi Redmi Note 13" degrades twice as fast after the sell-by date
     */
    public function testUpdateQualityForXiaomiRedmiNote13AfterSellIn(): void
    {
        $item = new Item('Xiaomi Redmi Note 13', 0, 20);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(16, $item->quality); // Degrades twice as fast
        $this->assertEquals(-1, $item->sellIn);
    }

    /**
     * testUpdateQualityForItemsAtMinimumQuality:
     * Ensures that an item's quality does not go below 0.
     */
    public function testUpdateQualityForItemsAtMinimumQuality(): void
    {
        $item = new Item('Regular Item', 10, 0);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(0, $item->quality); // Quality should not go below 0
    }

    /**
     * testUpdateQualityForItemsAtMaximumQuality:
     * Confirms that an item's quality does not exceed 50.
     */
    public function testUpdateQualityForItemsAtMaximumQuality(): void
    {
        $item = new Item('Apple AirPods', 10, 50);
        $service = new WolfService([$item]);

        $service->updateQuality();

        $this->assertEquals(50, $item->quality); // Quality should not exceed 50
    }
}
