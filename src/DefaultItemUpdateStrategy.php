<?php

namespace WolfShop;

class DefaultItemUpdateStrategy implements ItemUpdateStrategy
{
    public function update(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality--;
        }

        $item->sellIn--;

        if ($item->sellIn < 0 && $item->quality > 0) {
            $item->quality--;
        }
    }
}