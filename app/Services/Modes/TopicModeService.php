<?php

namespace App\Services\Modes;

use App\Models\Batch;
use App\Models\Article;
use App\Services\AIService;
use Illuminate\Support\Str;
use App\Helpers\PromptBuilder;
use Pgvector\Laravel\Distance;
use App\Models\SitemapEmbedding;
use App\Services\BraveSearchAPI;
use App\Helpers\ContentConverter;
use Illuminate\Support\Facades\Log;

class TopicModeService
{
    public static function generateArticles(Batch $batch)
    {
        $userPromptBuilder = new PromptBuilder();

        if ($batch->url) {
            $html = file_get_contents($batch->url);
            $userPromptBuilder->addWebsiteContent(
                ContentConverter::htmlToText($html)
            );
        }

        if ($batch->sitemap_url) {
            $sitemap = AIService::embedSitemap($batch->sitemap_url);
            $query_embedding = AIService::getEmbedding($batch->details);
            $internal_links = SitemapEmbedding::query()
                ->where('sitemap_id', $sitemap->id)
                ->nearestNeighbors('embedding', $query_embedding, Distance::L2)
                ->take(rand(2, 5))
                ->get()
                ->pluck('url');
            $userPromptBuilder->addInternalLinks($internal_links->toArray());
        }

        if ($batch->external_linking) {
            $userPromptBuilder->addExternalLinks(
                BraveSearchAPI::getExternalLinks($batch->details)
            );
        }

        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        $attempts_max = 1;
        $longestShortArticle = ["length" => 0, "content" => ""];

        $userPromptBuilder->setArticleTopic($batch->details)->setLanguage($batch->language);

        for ($attempts = 0; $attempts < $attempts_max; $attempts++) {
            $generatedArticle = AIService::sendPrompt(
                $systemPromptBuilder->build("HTML"),
                $userPromptBuilder->build("HTML"),
            );

            $articleHTML = $generatedArticle;

            if (strlen($articleHTML) < intval(env('MIN_ARTICLE_LENGTH'))) {
                if ($attempts < $attempts_max) {
                    if (strlen($articleHTML) > $longestShortArticle["length"]) {
                        $longestShortArticle = ["length" => strlen($articleHTML), "content" => $articleHTML];
                    }
                    Log::info("Article too short, retrying");
                    continue;
                } else {
                    $articleHTML = $longestShortArticle["content"];
                }
            }
        }

        $article = new Article();
        $article->id = Str::uuid();
        $htmlString = Str::replaceFirst('```html', '', $articleHTML);
        $htmlString = Str::replaceLast('```', '', $htmlString);
        $article->content = ContentConverter::convertHTMLToEditorJsBlocks($htmlString);
        $article->title = json_decode($article->content)->blocks[0]->data->text;
        $article->image_url = "https://source.unsplash.com/random/800x600";
        $article->batch_id = $batch->id;
        $article->user_id = $batch->user_id;
        $article->save();
    }
}
