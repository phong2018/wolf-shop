<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
    public function handle() // @phpstan-ignore-line
    {
        $response = Http::get('https://api.restful-api.dev/objects');

        if ($response->ok()) {
            $items = $response->json();

            foreach ($items as $item) {
                $this->info("Imported/Updated: {$item->name}");
            }
        } else {
            $this->error('Failed to fetch data from API.');
        }
    }
}
