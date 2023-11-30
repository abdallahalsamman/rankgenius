<?php

namespace App\Services;

use App\Models\Batch;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use App\Helpers\PromptBuilder;
use App\Models\Article;
use Illuminate\Support\Facades\Log;

class ArticleGenerationService
{
    public static function generateArticles(Batch $batch)
    {
        if ($batch->mode == BatchModeEnum::CONTEXT->value) {
            $articles = self::contextMode($batch);
        } else if ($batch->mode == BatchModeEnum::TITLE->value) {
            $articles = self::titleMode($batch->details, $batch->quantity);
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
            $userPromptBuilder->build()
        );

        $titles = $result['titles'];
        $businessSummary = $result['businessSummary'];


        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        for ($i = 0; $i < count($titles); $i++) {
            $title = $titles[$i];
            $userPromptBuilder->clear();
            $userPromptBuilder->setBusinessDescription($businessSummary)->setArticleTitle($title)->setLanguage($batch->language);

            // dd($systemPromptBuilder->build(), $userPromptBuilder->build());
            $generatedArticle = AIService::sendPrompt(
                $systemPromptBuilder->build(),
                $userPromptBuilder->build()
            );

            

            $markdown = self::convertToMarkdown($generatedArticle);

            if (strlen($markdown) < intval(env('MIN_ARTICLE_LENGTH'))) {
                Log::info("Article too short, skipping");
                $i--;
                continue;
            }

            $article = new Article();
            $article->id = Str::uuid();
            $article->title = $title;
            $article->content = $markdown;
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
