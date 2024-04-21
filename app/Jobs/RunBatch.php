<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\Preset;
use Illuminate\Bus\Queueable;
use App\Enums\BatchStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;

use Illuminate\Queue\InteractsWithQueue;
use App\Services\ArticleGenerationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RunBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 9999999;

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
    public $failOnTimeout = false;

    /**
     * Create a new job instance.
     */
    public function __construct(private Batch $batch)
    {
        Log::info('New job instance created with arguments: ' . json_encode(func_get_args()));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ArticleGenerationService::generateArticles($this->batch);

        $this->batch->update(['status' => BatchStatusEnum::DONE]);
    }
}
