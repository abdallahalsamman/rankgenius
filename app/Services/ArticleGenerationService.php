<?php

namespace App\Services;

use App\Models\Batch;
use App\Enums\BatchModeEnum;
use App\Services\Modes\TitleModeService;
use App\Services\Modes\TopicModeService;
use App\Services\Modes\PresetModeService;
use App\Services\Modes\KeywordModeService;

class ArticleGenerationService
{
    public static function generateArticles(Batch $batch)
    {
        match ($batch->mode) {
            BatchModeEnum::TOPIC->value => TopicModeService::generateArticles($batch),
            BatchModeEnum::TITLE->value => TitleModeService::generateArticles($batch),
            BatchModeEnum::KEYWORD->value => KeywordModeService::generateArticles($batch),
            default => PresetModeService::generateArticles($batch),
        };
    }
}
