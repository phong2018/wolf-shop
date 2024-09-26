<?php

namespace WolfShop;

class ItemUpdateStrategyFactory
{
    public static function getStrategy(Item $item): ItemUpdateStrategy
    {
        return match ($item->name) {
            'Apple AirPods' => new AppleAirPodsUpdateStrategy(),
            'Apple iPad Air' => new AppleIPadAirUpdateStrategy(),
            'Samsung Galaxy S23' => new SamsungGalaxyS23UpdateStrategy(),
            default => new DefaultItemUpdateStrategy(),
        };
    }
}