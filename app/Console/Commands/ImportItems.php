<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Item\ImportItemsService;
use Illuminate\Console\Command;

class ImportItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        resolve(ImportItemsService::class)->handle();
    }
}
