<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Eloquents\ItemRepositoryEloquents;
use App\Repositories\Interfaces\ItemRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(ItemRepository::class, ItemRepositoryEloquents::class);
    }
}
