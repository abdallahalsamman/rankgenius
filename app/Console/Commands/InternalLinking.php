<?php

namespace App\Console\Commands;

use App\Services\AIService;
use Pgvector\Laravel\Distance;
use Illuminate\Console\Command;
use App\Models\SitemapEmbedding;

class InternalLinking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:internal-linking {query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get relevant internal links for a given query embedding.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query_embedding_text = $this->argument('query');
        $query_embedding = AIService::generateEmbeddings([$query_embedding_text]);
        echo SitemapEmbedding::query()->nearestNeighbors('embedding', $query_embedding[0]['embedding'], Distance::L2)->take(20)->get()->pluck('url');
    }
}
