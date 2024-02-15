<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class AIService {
    public static function sendPrompt($systemMessage, $userMessage, $model = "gpt-3.5-turbo-16k", $maxtokens = 4000, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        
        $client = OpenAI::factory()
        ->withApiKey(config('services.openai.key'))
        ->withHttpClient(new \GuzzleHttp\Client(['timeout' => config('services.openai.timeout')]))
        ->make();

        Log::info('MODEL: ' . $model);
        Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);

        $additionalOptions = [];
        // if ($model == "gpt-3.5-turbo-1106" || $model == "gpt-4-1106-preview") {
        //     $additionalOptions = ['response_format' => ["type" => "json_object"]];
        // }

        $attempt = 0;
        $maxAttempts = 3;
        $result = null;
        while ($attempt < $maxAttempts && is_null($result)) {
            try {
                $result = $client->chat()->create(array_merge([
                    "model" => $model,
                    "temperature" => 0.7,
                    "top_p" => 1,
                    "frequency_penalty" => 0,
                    "presence_penalty" => 0,
                    'max_tokens' => $maxtokens,
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
            } catch(\Exception $e) {
                Log::error('Exception caught on openai Request: ' . $e->getMessage());
                $attempt++;
                sleep(1); // Wait a bit before retrying
            }
        }

        if (is_null($result)) {
            throw new \Exception("Failed to get a response after {$maxAttempts} attempts.");
        }
        Log::info('Received Prompt: ' . $result['choices'][0]['message']['content']);
        Log::info('Article Cost: (Prompt Tokens: ' . $result['usage']['prompt_tokens'] . ', Completion Tokens: ' . $result['usage']['completion_tokens'] . ') $' . (($result['usage']['prompt_tokens'] / 1000) * 0.001) + (($result['usage']['completion_tokens'] / 1000) * 0.002));

        if (isset($result['choices'][0]['finish_reason']) && $result['choices'][0]['finish_reason'] == "length") {
            Log::info('Received Stop Reason: ' . $result['choices'][0]['finish_reason']);
        }

        $rawContent = trim($result['choices'][0]['message']['content']);
        $content = json_decode($rawContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $content = $rawContent; // Fallback to raw content if not JSON
        }
        
        return $content;
    }

    public static function sendPromptStream($systemMessage, $userMessage, $model = "gpt-3.5-turbo-16k", $maxtokens = 4000, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
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
            'max_tokens' => $maxtokens,
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
