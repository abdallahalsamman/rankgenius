<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BraveSearchAPI
{
    public static function getExternalLinks($query)
    {
        $response = Http::withHeaders([
            'X-Subscription-Token' => env('BRAVE_SEARCH_API_KEY'),
            'Accept' => 'application/json',
        ])->get('https://api.search.brave.com/res/v1/web/search', [
            'q' => $query
        ]);

        $data = $response->json();
        $externalLinks = collect($data['web']['results'])->take(rand(1, 3))->map(function ($item) {
            return [
                'title' => $item['title'],
                'url' => $item['url']
            ];
        })->values()->reduce(function ($carry, $item) {
            $carry .= $item['url'] . ': ' . $item['title'] . "\n";
            return $carry;
        }, '');

        return $externalLinks;
    }
}
