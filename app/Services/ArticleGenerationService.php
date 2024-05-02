<?php

namespace App\Services;

use DOMDocument;
use App\Models\Batch;
use Alc\SitemapCrawler;
use App\Models\Article;
use App\Models\Sitemap;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use App\Helpers\PromptBuilder;
use Pgvector\Laravel\Distance;
use App\Models\SitemapEmbedding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ArticleGenerationService
{
    public static function generateArticles(Batch $batch)
    {
        if ($batch->mode == BatchModeEnum::TOPIC->value) {
            $articles = self::topicMode($batch);
        } else if ($batch->mode == BatchModeEnum::TITLE->value) {
            $articles = self::titleMode($batch);
        } else if ($batch->mode == BatchModeEnum::KEYWORD->value) {
            $articles = self::keywordMode($batch->details, $batch->quantity);
        } else if ($batch->mode == "PRESET") {
            $articles = self::presetMode($batch->details, $batch->quantity);
        }
    }

    public static function topicMode($batch)
    {
        $userPromptBuilder = new PromptBuilder();

        $url = $batch->url;
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $websiteHTML = file_get_contents($url);
            $websiteHTML = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $websiteHTML);
            $websiteHTML = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $websiteHTML);
            $websiteText = strip_tags($websiteHTML);
            $websiteText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $websiteText);

            $userPromptBuilder->addWebsiteContent($websiteText);
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

            // todo: remove cache once this hits production
            $cacheKey = 'sitemap_embeddings_' . md5($batch->sitemap_url);
            $embeddings = Cache::remember($cacheKey, 86400, function () use ($urls) {
                return AIService::generateEmbeddings($urls);
            });

            // $embeddings = AIService::generateEmbeddings($urls);

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

            $query_embedding = AIService::generateEmbeddings([$batch->details]);
            $nearestNeighbors = SitemapEmbedding::query()->nearestNeighbors('embedding', $query_embedding[0]['embedding'], Distance::L2)->take(rand(2, 5))->get()->pluck('url');
            $userPromptBuilder->addInternalLinks($nearestNeighbors->toArray());
        }

        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        $attempts_max = 4;
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

    public static function titleMode($batch)
    {
        $userPromptBuilder = new PromptBuilder();
        $userPromptBuilder->setLanguage($batch->language);

        $titles = explode("\n", $batch->details);

        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        $attempts = 0;
        $longestShortArticle = ["length" => 0, "content" => ""];

        for ($i = 0; $i < count($titles); $i++) {
            $title = $titles[$i];
            $userPromptBuilder->clear();
            $userPromptBuilder->setLanguage($batch->language)->setArticleTitle($title);

            $generatedArticle = AIService::sendPrompt(
                $systemPromptBuilder->build("HTML"),
                $userPromptBuilder->build("HTML"),
            );

            // $markdown = self::convertToMarkdown($generatedArticle);
            $markdown = $generatedArticle;

            if (strlen($markdown) < intval(env('MIN_ARTICLE_LENGTH'))) {
                if ($attempts < 3) {
                    if (strlen($markdown) > $longestShortArticle["length"]) {
                        $longestShortArticle = ["length" => strlen($markdown), "content" => $markdown];
                    }
                    Log::info("Article too short, retrying");
                    $i--;
                    $attempts++;
                    continue;
                } else {
                    $markdown = $longestShortArticle["content"];
                    $attempts = 0;
                    $longestShortArticle = ["length" => 0, "content" => ""];
                }
            } else {
                $attempts = 0;
                $longestShortArticle = ["length" => 0, "content" => ""];
            }

            $article = new Article();
            $article->id = Str::uuid();
            $htmlString = Str::replaceFirst('```html', '', $markdown);
            $htmlString = Str::replaceLast('```', '', $htmlString);
            $article->content = self::convertHTMLToEditorJsBlocks($htmlString);
            $article->title = json_decode($article->content)->blocks[0]->data->text;
            $article->image_url = "https://source.unsplash.com/random/800x600";
            $article->batch_id = $batch->id;
            $article->user_id = $batch->user_id;
            $article->save();
        }
    }


    public static function convertParagraphsToMarkdown($paragraphs)
    {
        $markdown = "";
        foreach ($paragraphs as $paragraph) {
            if (!empty($paragraph)) {
                $markdown .= $paragraph . "\n\n";
            }
        }

        return $markdown;
    }

    public static function convertContentToMarkdown($content)
    {
        $markdown = "";
        foreach ($content as $section) {
            $subHeading = $section["subHeading"] ?? $section["question"] ?? "";
            $paragraphs = $section["paragraphs"];

            if (!empty($subHeading)) {
                $markdown .= "### " . $subHeading . "\n\n";
            }

            $markdown .= self::convertParagraphsToMarkdown($paragraphs);
        }

        return $markdown;
    }

    public static function convertToMarkdown($data)
    {
        $markdown = "# " . $data["title"] . "\n\n";
        foreach ($data["articleBody"] as $section) {
            $heading = $section["heading"];
            $content = $section["content"];

            if (!empty($heading)) {
                $markdown .= "## " . $heading . "\n\n";
            }

            $markdown .= self::convertContentToMarkdown($content);
        }

        return $markdown;
    }

    public static function isEffectivelyEmpty($node)
    {
        if ($node->nodeType == XML_TEXT_NODE) {
            return trim($node->nodeValue) === '';
        }

        if ($node->nodeType == XML_ELEMENT_NODE) {
            foreach ($node->childNodes as $child) {
                if (!self::isEffectivelyEmpty($child)) {
                    return false;
                }
            }
        }

        return true; // No non-empty text nodes or elements found
    }

    public static function handleElement($element)
    {
        // Skip effectively empty elements
        if (self::isEffectivelyEmpty($element)) {
            return null;
        }

        $block = [];
        switch ($element->tagName) {
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'h7': // Though not standard, included for completeness
                $level = (int)substr($element->tagName, 1);
                $block = [
                    'type' => 'header',
                    'data' => [
                        'text' => $element->textContent,
                        'level' => $level
                    ]
                ];
                break;
            case 'p':
                $block = [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $element->textContent
                    ]
                ];
                break;
            case 'ul':
            case 'ol':
                $items = [];
                foreach ($element->childNodes as $li) {
                    if ($li->nodeType == XML_ELEMENT_NODE && $li->nodeName === 'li') {
                        // Handle nested lists or additional content within <li>
                        $liContent = '';
                        foreach ($li->childNodes as $childNode) {
                            if ($childNode->nodeType == XML_ELEMENT_NODE) {
                                // Recursively handle child elements (e.g., for nested lists)
                                $childBlock = self::handleElement($childNode);
                                if (!empty($childBlock)) {
                                    // For simplicity, concatenating child elements' text content
                                    $liContent .= $childBlock['data']['text'] . ' ';
                                }
                            } elseif ($childNode->nodeType == XML_TEXT_NODE) {
                                $liContent .= $childNode->nodeValue . ' ';
                            }
                        }
                        $items[] = trim($liContent);
                    }
                }
                $block = [
                    'type' => 'list',
                    'data' => [
                        'style' => $element->tagName === 'ol' ? 'ordered' : 'unordered',
                        'items' => $items
                    ]
                ];
                break;
                // case 'article':
                // case 'section':
                // case 'body':
                // case 'div':
            default:
                $nestedBlocks = [];
                foreach ($element->childNodes as $childNode) {
                    if ($childNode->nodeType == XML_ELEMENT_NODE) {
                        $childBlock = self::handleElement($childNode);
                        if (!empty($childBlock)) {
                            if (is_array(current($childBlock))) {
                                $nestedBlocks = array_merge($nestedBlocks, $childBlock);
                            } else {
                                $nestedBlocks[] = $childBlock;
                            }
                        }
                    }
                }
                // Instead of returning a single block, return an array of blocks
                return $nestedBlocks;
                break;
        }
        return $block;
    }

    public static function convertHTMLToEditorJsBlocks($htmlContent)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML('<html>' . $htmlContent . '</html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $blocks = [];

        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $block = self::handleElement($node);
                if (!empty($block)) {
                    if (is_array(current($block))) {
                        $blocks = array_merge($blocks, $block);
                    } else {
                        $blocks[] = $block;
                    }
                }
            }
        }

        $editorJsData = [
            'time' => time(),
            'blocks' => array_values(array_filter($blocks))
        ];

        return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function convertJSONToEditorJsBlocks($data)
    {
        $blocks = [];

        $blocks[] = [
            'type' => 'header',
            'data' => [
                'text' => $data['title'],
                'level' => 1
            ]
        ];

        foreach ($data['articleBody'] as $sectionKey => $section) {
            if (!empty($section['heading'])) {
                $blocks[] = [
                    'type' => 'header',
                    'data' => [
                        'text' => $section['heading'],
                        'level' => 2
                    ]
                ];
            }

            foreach ($section['content'] as $subsectionKey => $subsection) {
                if (!empty($subsection['subHeading']) || !empty($subsection['question'])) {
                    $subHeading = $subsection['subHeading'] ?? $subsection['question'];
                    $blocks[] = [
                        'type' => 'header',
                        'data' => [
                            'text' => $subHeading,
                            'level' => 3
                        ]
                    ];
                }

                foreach ($subsection['paragraphs'] as $paragraphKey => $paragraph) {
                    if (!empty($paragraph)) {
                        $blocks[] = [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => $paragraph
                            ]
                        ];
                    }
                }
            }
        }

        $editorJsData = [
            'time' => time(),
            'blocks' => $blocks,
            'version' => '2.22.2'
        ];

        return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // public static function titleMode($titles)
    // {
    //     $mode = BatchModeEnum::TITLE;
    //     $details = $titles;
    //     $quantity = substr_count($titles, "\n") + 1;
    //     return self::generateArticles($mode, $details, $quantity);
    // }

    // public static function keywordMode($keywords)
    // {
    //     $mode = BatchModeEnum::KEYWORD;
    //     $details = $keywords;
    //     $quantity = substr_count($keywords, "\n") + 1;
    //     return self::generateArticles($mode, $details, $quantity);
    // }

    // public static function presetMode($preset, $quantity)
    // {
    //     $preset = Preset::where('id', $preset)->first();
    //     $details = $preset->details;
    //     $mode = $preset->mode;
    //     return self::generateArticles($mode, $details, $quantity);
    // }
}
