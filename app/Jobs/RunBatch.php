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
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RunBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
