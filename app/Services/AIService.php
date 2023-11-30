<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class AIService {
    public static function sendPrompt($systemMessage, $userMessage, $maxtoken = 64, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::client(config('services.openai.key'));

        Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);
        $result = $client->chat()->create([
            "model" => "gpt-3.5-turbo-1106",
            // "model" => "babbage-002",
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 4000,
            'response_format' => ["type" => "json_object" ],
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
        ]);
        Log::info('Received Prompt: ' . $result['choices'][0]['message']['content']);
        Log::info('Article Cost: (Prompt Tokens: ' . $result['usage']['prompt_tokens'] . ', Completion Tokens: ' . $result['usage']['completion_tokens'] . ') $' . (($result['usage']['prompt_tokens'] / 1000) * 0.001) + (($result['usage']['completion_tokens'] / 1000) * 0.002));

        if (isset($result['choices'][0]['finish_reason']) && $result['choices'][0]['finish_reason'] == "stop") {
            Log::info('Received Stop Reason: ' . $result['choices'][0]['finish_reason']);
            return $result;
        }

        $content = json_decode($result['choices'][0]['message']['content'], true);
        return $content;
    }
}
