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
    ) { }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $strategy = ItemUpdateStrategyFactory::getStrategy($item);
            $strategy->update($item);
        }
    }
}