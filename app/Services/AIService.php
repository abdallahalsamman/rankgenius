<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class AIService {
    public static function sendPrompt($systemMessage, $userMessage, $model = "gpt-3.5-turbo-16k", $maxtoken = 64, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::client(config('services.openai.key'));

        Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);

        $additionalOptions = [];
        // if ($model == "gpt-3.5-turbo-1106") {
        //     $additionalOptions = ['response_format' => ["type" => "json_object" ]];
        // }

        $result = $client->chat()->create(array_merge([
            "model" => $model,
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 10000,
            'messages' => [
                [
                    "role" => "system",
                    "content" => $systemMessage,
                ],
                [
                    "role" => "user",
                    "content" => $userMessage,
                ]
            ],
        ], $additionalOptions));

        Log::info('Received Prompt: ' . $result['choices'][0]['message']['content']);
        Log::info('Article Cost: (Prompt Tokens: ' . $result['usage']['prompt_tokens'] . ', Completion Tokens: ' . $result['usage']['completion_tokens'] . ') $' . (($result['usage']['prompt_tokens'] / 1000) * 0.001) + (($result['usage']['completion_tokens'] / 1000) * 0.002));

        if (isset($result['choices'][0]['finish_reason']) && $result['choices'][0]['finish_reason'] == "length") {
            Log::info('Received Stop Reason: ' . $result['choices'][0]['finish_reason']);
        }

        $content = json_decode(trim($result['choices'][0]['message']['content']), true);
        return $content;
    }

    public static function sendPromptStream($systemMessage, $userMessage, $model = "gpt-3.5-turbo-16k", $maxtoken = 64, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::client(config('services.openai.key'));

        Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);

        $additionalOptions = [];
        // if ($model == "gpt-3.5-turbo-1106") {
        //     $additionalOptions = ['response_format' => ["type" => "json_object" ]];
        // }

        $stream = $client->chat()->createStreamed(array_merge([
            "model" => $model,
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 10000,
            'messages' => [
                [
                    "role" => "system",
                    "content" => $systemMessage,
                ],
                [
                    "role" => "user",
                    "content" => $userMessage,
                ]
            ],
        ], $additionalOptions));

        Log::info('Received Prompt: ');
        $responseText = '';
        foreach($stream as $response){
            $text = $response->choices[0]->delta->content;
            Log::info($text);
            $responseText .= $text;
        }

        $content = json_decode(trim($responseText), true);
        return $content;
    }
}
