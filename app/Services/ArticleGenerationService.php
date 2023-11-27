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

        // $url = substr($batch->details, 0, strpos($batch->details, "\n"));
        // if (filter_var($url, FILTER_VALIDATE_URL)) {
        //     $websiteHTML = file_get_contents($url);
        //     $websiteText = strip_tags($websiteHTML);
        //     $websiteText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $websiteText);

        //     $userPromptBuilder->addWebsiteContent($websiteText);
        // }

        // $userPromptBuilder->setBusinessDescription($batch->details)->getBusinessSummary()->getArticleSuggestions($batch->quantity);

        // $result = AIService::sendPrompt(
        //     "",
        //     $userPromptBuilder->build()
        // );

        // $titles = $result['titles'];
        // $businessSummary = $result['businessSummary'];

        $titles = [
            "10 Best Apartment-Friendly Dog Breeds",
            "Tips for Training a Dog in a Small Space",
            "Choosing the Right Size Dog for Your Apartment",
            "Pet-Friendly Apartment Living: What You Need to Know",
            "Creating a Dog-Friendly Apartment: Tips for Apartment Owners",
            "The Benefits of Having a Dog in an Apartment",
            "Dog Breeds That Thrive in Urban Environments",
            "Apartment Living with Large Dog Breeds: What You Should Consider",
            "Adopting a Dog in the City: What You Should Know",
            "Pet Care Services for Apartment Owners",
            "The Importance of Exercise for Apartment Dogs",
            "Dog-Proofing Your Apartment: Essential Tips for Apartment Owners",
            "Understanding the Needs of Apartment Dogs",
            "Socializing Your Dog in an Urban Environment",
            "Balcony Safety for Apartment Dogs",
            "Apartment-Friendly Cats: Tips for Feline Companions in Small Spaces",
            "Maximizing Space for Pets in Your Apartment",
            "The Impact of Pets on Apartment Rental Agreements",
            "Choosing the Right Apartment for You and Your Pet",
            "Creating a Pet-Friendly Community in Your Apartment Building"
        ];

        $businessSummary = "Your business provides information and resources for apartment owners who are considering getting a pet. You offer advice on choosing the right breed for apartment living, training tips, and pet care services tailored for urban dwellers.";

        $systemPromptBuilder = new PromptBuilder();
        $systemPromptBuilder->addOutline();

        $titles = [$titles[9]];
        foreach ($titles as $title) {
            $userPromptBuilder->clear();
            $userPromptBuilder->setBusinessDescription($businessSummary)->setArticleTitle($title);

            // dd($systemPromptBuilder->build(), $userPromptBuilder->build());
            $generatedArticle = AIService::sendPrompt(
                $systemPromptBuilder->build(),
                $userPromptBuilder->build()
            );

            $markdown = self::convertToMarkdown($generatedArticle);

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
            $subHeading = $section["subHeading"];
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
