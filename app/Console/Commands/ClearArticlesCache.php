<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;


class ClearArticlesCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-articles-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cache associated with articles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define the cache tag associated with articles
        $cacheTag = 'articles';

        // Clear the cache associated with the articles tag
        Cache::tags([$cacheTag])->flush();

        $this->info('Articles cache cleared successfully.');
    }
}
