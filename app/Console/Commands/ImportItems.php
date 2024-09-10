<?php

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
    public function handle()
    {
        $response = Http::get('https://api.restful-api.dev/objects');

        if ($response->ok()) {
            $items = $response->json();

            foreach ($items as $data) {
                $item = Item::updateOrCreate(
                    ['name' => $data['name']],
                    ['sell_in' => $data['sellIn'], 'quality' => $data['quality']]
                );

                $this->info("Imported/Updated: {$item->name}");
            }
        } else {
            $this->error('Failed to fetch data from API.');
        }
    }
}
