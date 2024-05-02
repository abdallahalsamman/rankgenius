<?php

namespace App\Services\Modes;


use App\Models\Batch;
use App\Helpers\PromptBuilder;

class TitleModeService
{
    public static function generateArticles(Batch $batch)
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
}
