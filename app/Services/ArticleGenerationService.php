<?php

namespace App\Services;

use DOMDocument;
use App\Models\Batch;
use Alc\SitemapCrawler;
use App\Models\Article;
use App\Models\Sitemap;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use Pgvector\Laravel\Distance;
use App\Models\SitemapEmbedding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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
