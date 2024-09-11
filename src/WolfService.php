<?php

declare(strict_types=1);

namespace WolfShop;

final class WolfService
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    /**
     * Updates the quality and sell-in values of all items in the inventory.
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case 'Apple AirPods':
                    $this->updateAppleAirPods($item);
                    break;

                case 'Apple iPad Air':
                    $this->updateAppleIpadAir($item);
                    break;

                case 'Samsung Galaxy S23':
                    // legendary item and as such its Quality is 80 & it never alters.
                    break;

                case 'Xiaomi Redmi Note 13':
                    $this->updateXiaomiRedmiNote13($item);
                    break;

                default:
                    $this->updateRegularItem($item);
                    break;
            }
        }
    }

    /**
     * Updates the quality and sell-in values for "Apple AirPods".
     * + increases in Quality the older it gets
     * + the Quality of an item is never more than 50
     *
     * @param Item $item The item to update.
     */
    private function updateAppleAirPods(Item $item): void
    {
        if ($item->quality < 50) {
            $item->quality++;
        }
        $item->sellIn--;
    }

    /**
     * For "Apple iPad Air".
     * The quality increases as the sell-in date approaches:
     * + Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less but
     * + Quality drops to 0 after the concert
     * + Quality is capped at 50.
     *
     * @param Item $item The item to update.
     */
    private function updateAppleIpadAir(Item $item): void
    {
        if ($item->sellIn <= 0) {
            $item->quality = 0;
        } else {
            if ($item->sellIn <= 5) {
                $item->quality += 3;
            } elseif ($item->sellIn <= 10) {
                $item->quality += 2;
            } else {
                ++$item->quality;
            }
            if ($item->quality > 50) {
                $item->quality = 50;
            }
        }
        $item->sellIn--;
    }

    /**
     * For "Xiaomi Redmi Note 13".
     * Items degrade in Quality twice as fast as normal items
     *
     * @param Item $item The item to update.
     */
    private function updateXiaomiRedmiNote13(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality -= 2;
        }
        $item->sellIn--;
        if ($item->sellIn < 0) {
            if ($item->quality > 0) {
                $item->quality -= 2;
            }
        }
    }

    /**
     * For regular items.
     * The sell-in value decreases by 1 each day.
     * Once the sell by date has passed, Quality degrades twice as fast
     * Quality is never negative.
     *
     * @param Item $item The item to update.
     */
    private function updateRegularItem(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality--;
        }
        $item->sellIn--;
        if ($item->sellIn < 0) {
            if ($item->quality > 0) {
                $item->quality--;
            }
        }
    }
}
