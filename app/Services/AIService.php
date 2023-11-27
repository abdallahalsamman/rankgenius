<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class AIService {
    public static function sendPrompt($systemMessage, $userMessage, $maxtoken = 64, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        Log::info('Sending Prompt: ' . json_encode(func_get_args()));
        $result = $client->chat()->create([
            "model" => "gpt-3.5-turbo-1106",
            // "model" => "babbage-002",
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 3500,
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
        Log::info('Received Prompt: ' . json_encode($result));

        $content = json_decode($result['choices'][0]['message']['content'], true);
        return $content;
    }
}
