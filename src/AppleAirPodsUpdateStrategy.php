<?php

namespace WolfShop;

class AppleAirPodsUpdateStrategy implements ItemUpdateStrategy
{
    public function update(Item $item): void
    {
        if ($item->quality < 50) {
            $item->quality++;
        }

        $item->sellIn--;

        if ($item->sellIn < 0 && $item->quality < 50) {
            $item->quality++;
        }
    }
}