<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;

class ShowItemService extends BaseService
{
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
        return $this->repository->show($this->model, $this->data); // @phpstan-ignore-line
    }
}