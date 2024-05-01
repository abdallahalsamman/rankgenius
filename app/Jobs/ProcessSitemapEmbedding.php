<?php

namespace App\Jobs;

use App\Models\SitemapEmbedding;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSitemapEmbedding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 20;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 3;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    protected $url;
    protected $sitemapId;

    public function __construct($url, $sitemapId)
    {
        $this->url = $url;
        $this->sitemapId = $sitemapId;
    }

    public function handle()
    {
        // $embedding = AIService::generateEmbeddings([$this->url])[0];

        // SitemapEmbedding::create([
        //     'url' => $embedding['text'],
        //     'embedding' => $embedding['embedding'],
        //     'sitemap_id' => $this->sitemapId
        // ]);
    }
}
