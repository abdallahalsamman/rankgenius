<?php

namespace App\Services\Modes;

use App\Models\Batch;
use App\Helpers\PromptBuilder;

class TopicModeService
{
    public static function generateArticles(Batch $batch)
    {
        $userPromptBuilder = new PromptBuilder();

        if ($batch->url) {
            $websiteHTML = file_get_contents($batch->url);
            $dom = new DOMDocument();
            @$dom->loadHTML($websiteHTML, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $userPromptBuilder->addWebsiteContent($dom->textContent);
        }

        if ($batch->sitemap_url) {
            // this is required because SitemapCrawler uses an underlying 
            // library which parses XML and has a default MAX_FILE_SIZE of 600000
            // which is too small for large sitemaps
            if (!defined('MAX_FILE_SIZE')) {
                define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 5 MB
            }

            $crawler = new SitemapCrawler();

            $cacheKey = 'sitemap_data_' . md5($batch->sitemap_url);
            $urls = Cache::remember($cacheKey, 86400, function () use ($crawler, $batch) {
                return array_keys($crawler->crawl($batch->sitemap_url));
            });

            $sitemap = Sitemap::updateOrCreate([
                'url' => $batch->sitemap_url
            ], ['last_fetched' => now()]);

            // keep only urls that are not already in the database
            $urls = array_diff($urls, $sitemap->embeddings->pluck('url')->toArray());

            if (count($urls) > 0) {
                $embeddings = AIService::generateEmbeddings($urls);

                $embeddingsToInsert = [];
                foreach ($embeddings as $embedding) {
                    $embeddingsToInsert[] = [
                        'url' => $embedding['text'],
                        'embedding' => json_encode($embedding['embedding']),
                        'sitemap_id' => $sitemap->id
                    ];

                    if (count($embeddingsToInsert) == 100) {
                        SitemapEmbedding::insert($embeddingsToInsert);
                        $embeddingsToInsert = [];
                    }
                }

                if (count($embeddingsToInsert) > 0) {
                    SitemapEmbedding::insert($embeddingsToInsert);
                    $embeddingsToInsert = [];
                }
            }

            $query_embedding = AIService::generateEmbeddings([$batch->details]);
            $nearestNeighbors = SitemapEmbedding::query()->nearestNeighbors('embedding', $query_embedding[0]['embedding'], Distance::L2)->take(rand(2, 5))->get()->pluck('url');
            $userPromptBuilder->addInternalLinks($nearestNeighbors->toArray());
        }

        if ($batch->external_linking) {
            $response = Http::withHeaders([
                'X-Subscription-Token' => env('BRAVE_SEARCH_API_KEY'),
                'Accept' => 'application/json',
            ])->get('https://api.search.brave.com/res/v1/web/search', [
                'q' => $batch->details
            ]);

            $data = $response->json();
            $externalLinks = collect($data['web']['results'])->take(rand(1, 3))->map(function ($item) {
                return [
                    'title' => $item['title'],
                    'url'   => $item['url']
                ];
            })->values()->reduce(function ($carry, $item) {
                $carry .= $item['url'] . ': ' . $item['title'] . "\n";
                return $carry;
            }, '');

            $userPromptBuilder->addExternalLinks($externalLinks);
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
                "gpt-4-1106-preview"
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
        $article->content = self::convertHTMLToEditorJsBlocks($htmlString);
        $article->title = json_decode($article->content)->blocks[0]->data->text;
        $article->image_url = "https://source.unsplash.com/random/800x600";
        $article->batch_id = $batch->id;
        $article->user_id = $batch->user_id;
        $article->save();
    }
}
