<?php

namespace App\Services;

use OpenAI;
use Alc\SitemapCrawler;
use App\Models\Sitemap;
use App\Models\SitemapEmbedding;
use Illuminate\Support\Facades\Log;
use Yethee\Tiktoken\EncoderProvider;
use Illuminate\Support\Facades\Cache;

class AIService
{

    static $PROMPT_PRICING = [
        "gpt-3.5-turbo-16k-0613" => 0.0030,
        "gpt-3.5-turbo-16k" => 0.0030,
        "gpt-3.5-turbo-1106" => 0.0010,
        "gpt-4-1106-preview" => 0.01,
        "gpt-3.5-turbo-0613" => 0.0015,
        "gpt-3.5-turbo-0301" => 0.0015,
        "gpt-3.5-turbo-0125" => 0.0005,
        "gpt-4" => 0.03,
        "gpt-4-32k" => 0.06,
        "gpt-4-0125-preview" => 0.01,
    ];

    static $RESPONSE_PRICING = [
        "gpt-3.5-turbo-16k-0613" => 0.0040,
        "gpt-3.5-turbo-16k" => 0.0040,
        "gpt-3.5-turbo-1106" => 0.0020,
        "gpt-4-1106-preview" => 0.03,
        "gpt-3.5-turbo-0613" => 0.0020,
        "gpt-3.5-turbo-0301" => 0.0020,
        "gpt-3.5-turbo-0125" => 0.0015,
        "gpt-4" => 0.06,
        "gpt-4-32k" => 0.12,
        "gpt-4-0125-preview" => 0.03,
    ];

    public static function sendPrompt($systemMessage, $userMessage, $model = "gpt-4-0125-preview", $maxtokens = 4000, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::factory()
            ->withApiKey(config('services.openai.key'))
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => config('services.openai.timeout')]))
            ->make();

        Log::info('MODEL: ' . $model);
        Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);

        $additionalOptions = [];
        // if ($model == "gpt-3.5-turbo-1106" || $model == "gpt-4-1106-preview") {
        //     $additionalOptions = ['response_format' => ["type" => "json_obubject"]];
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
            } catch (\Exception $e) {
                Log::error('Exception caught on openai Request: ' . $e->getMessage());
                $attempt++;
                sleep(1); // Wait a bit before retrying
            }
        }

        if (is_null($result)) {
            throw new \Exception("Failed to get a response after {$maxAttempts} attempts.");
        }
        Log::info('Received Prompt: ' . $result['choices'][0]['message']['content']);
        Log::info('Article Cost: (Prompt Tokens: ' . $result['usage']['prompt_tokens'] . ', Completion Tokens: ' . $result['usage']['completion_tokens'] . ')' .
            '$' . (($result['usage']['prompt_tokens'] / 1000) * self::$PROMPT_PRICING[$model]) + (($result['usage']['completion_tokens'] / 1000) * self::$RESPONSE_PRICING[$model]));

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

    public static function sendPromptStream($systemMessage, $userMessage, $model = "gpt-4-0125-preview", $maxtokens = 4000, $temperature = 0.7, $topP = 1, $frequencyPenalty = 0, $presencePenalty = 0, $stopSequences = [])
    {
        $client = OpenAI::client(config('services.openai.key'));

        // Log::info('SYSTEM PROMPT: ' . $systemMessage . "\n\nUSER MESSAGE: " . $userMessage);

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
        foreach ($stream as $response) {
            $text = $response->choices[0]->delta->content;
            Log::info($text);
            $responseText .= $text;
        }

        $content = json_decode(trim($responseText), true);
        return $content;
    }

    public static function &generateEmbeddings($data)
    {
        $client = OpenAI::client(config('services.openai.key'));

        $provider = new EncoderProvider();
        $encoder = $provider->getForModel('gpt-3.5-turbo'); // because it uses cl100k_base like text-embedding-3-small

        // create chunks of urls to embed to avoid hitting the token limit
        $chunk = [];
        $tokenLimit = 8191;
        $tokenCounter = 0;
        Log::info('Initiating embedding ' . count($data) . ' urls');
        foreach ($data as $item) {
            $tokenCount = count($encoder->encode($item));
            if ($tokenCounter + $tokenCount > $tokenLimit) {
                Log::info(
                    'Sending ' . count($chunk) . ' urls to embed'
                );
                $embeddings = $client->embeddings()->create([
                    'model' => 'text-embedding-3-small',
                    'input' => $chunk
                ]);

                $results = [];
                foreach ($embeddings['data'] as $embedding) {
                    $results[] = [
                        'text' => $chunk[$embedding['index']],
                        'embedding' => $embedding['embedding']
                    ];
                }

                yield $results;
                $chunk = [];
                $tokenCounter = 0;
            }

            $chunk[] = $item;
            $tokenCounter += $tokenCount;
        }

        if (count($chunk) > 0) {
            Log::info(
                'Sending ' . count($chunk) . ' urls to embed'
            );
            $embeddings = $client->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $chunk
            ]);

            $results = [];
            foreach ($embeddings['data'] as $embedding) {
                $results[] = [
                    'text' => $chunk[$embedding['index']],
                    'embedding' => $embedding['embedding']
                ];
            }

            yield $results;
        }
    }

    public static function getEmbedding($data)
    {
        $client = OpenAI::client(config('services.openai.key'));
        $provider = new EncoderProvider();
        $encoder = $provider->getForModel('gpt-3.5-turbo'); // because it uses cl100k_base like text-embedding-3-small
        $tokenCount = count($encoder->encode($data));

        if ($tokenCount > 8191) {
            throw new \Exception('Input is too large to embed');
        }

        $embedding = $client->embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $data
        ])['data'][0];

        return $embedding['embedding'];
    }

    public static function embedSitemap($sitemap_url)
    {
        // this is required because SitemapCrawler uses an underlying 
        // library which parses XML and has a default MAX_FILE_SIZE of 600000
        // which is too small for large sitemaps
        if (!defined('MAX_FILE_SIZE')) {
            define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 5 MB
        }

        $crawler = new SitemapCrawler();

        $cacheKey = 'sitemap_data_' . md5($sitemap_url);
        $urlsNotClean = Cache::remember($cacheKey, 86400, function () use ($crawler, $sitemap_url) {
            return array_keys($crawler->crawl($sitemap_url));
        });

        // if urlsNotClean have <![CDATA[ and ]]> remove them
        $urls = array_map(function ($url) {
            return str_replace(['<![CDATA[', ']]>'], '', $url);
        }, $urlsNotClean);

        $sitemap = Sitemap::updateOrCreate([
            'url' => $sitemap_url
        ], ['last_fetched' => now()]);

        // keep only urls that are not already in the database
        $urls = array_diff($urls, $sitemap->embeddings->pluck('url')->toArray());
        Log::info('Embedding ' . count($urls) . ' urls');

        if (count($urls) > 0) {
            foreach (self::generateEmbeddings($urls) as $embeddings) {
                Log::info('Inserting ' . count($embeddings) . ' embeddings from ' . $sitemap->url);
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
        }

        return $sitemap;
    }
}
