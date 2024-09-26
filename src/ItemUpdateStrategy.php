<?php

namespace WolfShop;

interface ItemUpdateStrategy
{
    public function update(Item $item): void;
}