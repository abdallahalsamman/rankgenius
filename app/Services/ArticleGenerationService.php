<?php

namespace App\Services;

use App\Models\Batch;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use App\Helpers\PromptBuilder;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use DOMDocument;

class ArticleGenerationService
{
    public static function generateArticles(Batch $batch)
    {
        if ($batch->mode == BatchModeEnum::CONTEXT->value) {
            $articles = self::contextMode($batch);
        } else if ($batch->mode == BatchModeEnum::TITLE->value) {
            $articles = self::titleMode($batch);
        } else if ($batch->mode == BatchModeEnum::KEYWORD->value) {
            $articles = self::keywordMode($batch->details, $batch->quantity);
        } else if ($batch->mode == "PRESET") {
            $articles = self::presetMode($batch->details, $batch->quantity);
        }
    }

    public static function contextMode($batch)
    {
        $userPromptBuilder = new PromptBuilder();

        $url = substr($batch->details, 0, strpos($batch->details, "\n"));
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $websiteHTML = file_get_contents($url);
            $websiteHTML = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $websiteHTML);
            $websiteHTML = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $websiteHTML);
            $websiteText = strip_tags($websiteHTML);
            $websiteText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $websiteText);

            $userPromptBuilder->addWebsiteContent($websiteText);
        }

        $userPromptBuilder->setBusinessDescription($batch->details)->getBusinessSummary()->getArticleSuggestions($batch->quantity)->setLanguage($batch->language);

        $result = AIService::sendPrompt(
            "",
            $userPromptBuilder->build("JSON")
        );

        $titles = $result['titles'];
        $businessSummary = $result['businessSummary'];


        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        $attempts = 0;
        $longestShortArticle = ["length" => 0, "content" => ""];
        
        for ($i = 0; $i < count($titles); $i++) {
            $title = $titles[$i];
            $userPromptBuilder->clear();
            $userPromptBuilder->setBusinessDescription($businessSummary)->setArticleTitle($title)->setLanguage($batch->language);
        
            $generatedArticle = AIService::sendPrompt(
                $systemPromptBuilder->build("HTML"),
                $userPromptBuilder->build("HTML"),
            );
        
            // $markdown = self::convertToMarkdown($generatedArticle);
            $markdown = $generatedArticle;
        
            if (strlen($markdown) < intval(env('MIN_ARTICLE_LENGTH'))) {
                if ($attempts < 3) {
                    if(strlen($markdown) > $longestShortArticle["length"]) {
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
            $article->title = $title;
            Log::info("Markdown: " . $markdown);
            $article->content = self::convertHTMLToEditorJsBlocks($markdown);
            $article->image_url = "https://source.unsplash.com/random/800x600";
            $article->batch_id = $batch->id;
            $article->user_id = $batch->user_id;
            $article->save();
        }
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
                $systemPromptBuilder->build("JSON"),
                $userPromptBuilder->build("JSON"),
            );
        
            // $markdown = self::convertToMarkdown($generatedArticle);
            $markdown = $generatedArticle;
        
            if (strlen($markdown) < intval(env('MIN_ARTICLE_LENGTH'))) {
                if ($attempts < 3) {
                    if(strlen($markdown) > $longestShortArticle["length"]) {
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
            $article->title = $title;
            Log::info("Markdown: " . $markdown);
            $article->content = self::convertHTMLToEditorJsBlocks($markdown);
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
            if (! empty($paragraph)) {
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

            if (! empty($subHeading)) {
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

            if (! empty($heading)) {
                $markdown .= "## " . $heading . "\n\n";
            }

            $markdown .= self::convertContentToMarkdown($content);
        }

        return $markdown;
    }

    public static function isEffectivelyEmpty($node) {
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

    public static function handleElement($element) {
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
        }
        return $block;
    }

    public static function convertHTMLToEditorJsBlocks($htmlContent)
    {
        $doc = new DOMDocument();
        // Suppress warnings with '@' and load the HTML content into the DOMDocument object.
        // 'LIBXML_HTML_NOIMPLIED' prevents the addition of implied html/body elements,
        // and 'LIBXML_HTML_NODEFDTD' prevents the addition of a default doctype if none exists.
        @$doc->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Prepare the Editor.js blocks array
        $blocks = [];

        // Iterate through body child nodes to convert to Editor.js blocks
        foreach ($doc->getElementsByTagName('body')->item(0)->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $block = self::handleElement($node);
                if (!empty($block)) {
                    $blocks[] = $block;
                }
            }
        }

        // Construct the final Editor.js data structure
        $editorJsData = [
            'time' => time(),
            'blocks' => $blocks
        ];

        // Output the JSON representation of the Editor.js data
        return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function convertJSONToEditorJsBlocks($data) {
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
