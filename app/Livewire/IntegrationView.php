<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use App\Models\Integration;
use Illuminate\Support\Str;
use App\Models\IntegrationType;
use App\Enums\IntegrationTypeEnum;
use App\Models\ShopifyIntegration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WordpressIntegration;
use Illuminate\Support\Facades\Route;

use Signifly\Shopify\Shopify;
use MadeITBelgium\WordPress\WordPress;
use Usernotnull\Toast\Concerns\WireToast;

class IntegrationView extends Component
{
    use WireToast;

    public $selectedTab = 'shopify';

    public $action, $integrationId;
    public $wordpressIntegrationId, $shopifyIntegrationId;
    public $categoriesOptions = [], $tagsOptions = [], $statusesOptions = [], $authorsOptions = [];
    public $savedAuthorOption = [], $savedCategoriesOption = [], $savedTagsOption = [];

    public $shopifyBlogs = [], $shopifyAuthors = [];
    public $shopifyIntegration = [
        "shop_name" => "0ae717-b8",
        "access_token" => "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",
        "blog" => null,
        "author" => null,
    ];

    public $integration = [
        'name' => "test",
    ];

    public $wordpressIntegration = [
        "url" => "https://www.aiobot.com/",
        "username" => "sammanabdallah",
        "app_password" => "UaoK qroT uPIC 7y4F I8GU PcGk",
        "status" => "draft",
        "categories" => [],
        "tags" => [],
        "author" => 1,
        "time_gap" => 0,

        // "url" => "",
        // "username" => "",
        // "app_password" => "",
        // "status" => "publish",
        // "categories" => [],
        // "tags" => [],
        // "author" => 1,
        // "time_gap" => 0,
    ];

    public function getWordPressSavedInfo()
    {
        $savedAuthor = $this->wordpressIntegration['author'];
        $savedCategories = $this->wordpressIntegration['categories'];
        $savedTags = $this->wordpressIntegration['tags'];

        $wp = (new WordPress($this->wordpressIntegration['url']))
            ->setUsername($this->wordpressIntegration['username'])
            ->setApplicationPassword($this->wordpressIntegration['app_password']);

        $this->savedAuthorOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/users?include=' . $savedAuthor)
        );

        $this->savedCategoriesOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/categories?include=' . implode(',', $savedCategories))
        );

        $this->savedTagsOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/tags?include=' . implode(',', $savedTags))
        );
    }

    public function updateShopifyInfo()
    {
        $this->shopifyAuthors = [];
        $this->shopifyBlogs = [];

        $shop_name = $this->shopifyIntegration['shop_name'];
        $access_token = $this->shopifyIntegration['access_token'];

        if (!empty($shop_name) && !empty($access_token)) {
            try {
                $shopify = new Shopify($access_token, $shop_name . '.myshopify.com', '2024-04');

                $shopify->getBlogsCount();

                toast()->success('Logged in successfully to shopify')->push();

                $this->shopifyBlogs = $shopify->getBlogs()->toArray();
                $shopifyAuthors = $shopify->getArticleAuthors();
                $this->shopifyAuthors = array_map(function ($name) {
                    return ['id' => $name, 'name' => $name];
                }, $shopifyAuthors);

                if ($this->action == 'create') {
                    $this->shopifyIntegration['author'] = $shopifyAuthors[0];
                    $this->shopifyIntegration['blog'] = $this->shopifyBlogs[0]['id'];
                }

                // Route::currentRouteName() on mount returns route name "integration.create"
                // While on /update returns "livewire.update"
                // we only want to set the author to default to the first author in authorsOptions
            } catch (Exception $e) {
                Log::error($e);

                // $errorMap = [
                //     [
                //         'match' => 'Could not resolve host: ',
                //         'text' => $website . ' is not responding.',
                //     ],
                //     [
                //         'match' => 'Unauthorized',
                //         'text' => 'Invalid Username or Application Password.',
                //     ],
                // ];

                $error_text = $e->getMessage();
                // foreach ($errorMap as $error) {
                //     if (Str::contains($e->getMessage(), $error['match'])) {
                //         $error_text = $error['text'];
                //         break;
                //     }
                // }

                toast()
                    ->danger(strip_tags($error_text))
                    ->duration(3000)->push();
            }
        }
    }


    public function updateWordPressInfo()
    {
        $this->tagsOptions = [];
        $this->statusesOptions = [];
        $this->categoriesOptions = [];
        $this->authorsOptions = [];

        $website = $this->wordpressIntegration['url'];
        $username = $this->wordpressIntegration['username'];
        $app_password = $this->wordpressIntegration['app_password'];
        $app_password = str_replace(' ', '', $app_password);

        if (!empty($website) && !empty($username) && !empty($app_password)) {
            try {
                $wp = (new WordPress($website))->setUsername($username)->setApplicationPassword($app_password);
                // Auth Test
                $tag = $wp->postCall('/wp-json/wp/v2/tags', ['name' => env('APP_NAME') . '_test']);
                $wp->deleteCall('/wp-json/wp/v2/tags/' . $tag->id . '?force=true');

                $tags = $wp->getCall('/wp-json/wp/v2/tags?per_page=100');
                $statuses = $wp->getCall('/wp-json/wp/v2/statuses?per_page=100');
                $categories = $wp->getCall('/wp-json/wp/v2/categories?per_page=100');
                $authors = $wp->getCall('/wp-json/wp/v2/users?per_page=100');

                toast()->success('Logged in successfully')->push();

                if ($this->action == 'edit') {
                    $this->getWordPressSavedInfo();
                }

                $this->authorsOptions = $this->makeOptions($authors);
                $this->tagsOptions = $this->makeOptions($tags);
                $this->statusesOptions = $this->makeOptions($statuses);
                $this->categoriesOptions = $this->makeOptions($categories);

                if ($this->action == 'edit') {
                    $this->authorsOptions = array_merge($this->authorsOptions, $this->savedAuthorOption);
                    $this->tagsOptions = array_merge($this->tagsOptions, $this->savedTagsOption);
                    $this->categoriesOptions = array_merge($this->categoriesOptions, $this->savedCategoriesOption);
                }

                // Route::currentRouteName() on mount returns route name "integration.create"
                // While on /update returns "livewire.update"
                // we only want to set the author to default to the first author in authorsOptions
            } catch (Exception $e) {
                Log::error($e);

                $errorMap = [
                    [
                        'match' => 'Could not resolve host: ',
                        'text' => $website . ' is not responding.',
                    ],
                    [
                        'match' => 'Unauthorized',
                        'text' => 'Invalid Username or Application Password.',
                    ],
                ];

                $error_text = $e->getMessage();
                foreach ($errorMap as $error) {
                    if (Str::contains($e->getMessage(), $error['match'])) {
                        $error_text = $error['text'];
                        break;
                    }
                }

                toast()
                    ->danger(strip_tags($error_text))
                    ->duration(3000)->push();
            }
        }
    }

    public function updated($key, $value)
    {
        if (in_array($key, ['wordpressIntegration.url', 'wordpressIntegration.username', 'wordpressIntegration.app_password'])) {
            $this->updateWordPressInfo();
        }

        if (in_array($key, ['shopifyIntegration.shop_name', 'shopifyIntegration.access_token'])) {
            $this->updateShopifyInfo();
        }
    }

    private function makeOptions($data)
    {
        $options = [];
        foreach ($data as $item) {
            $item = (array) $item;
            $options[] = [
                'id' => $item['id'] ?? $item['slug'],
                'name' => $item['name'],
            ];
        }
        return $options;
    }

    public function searchUsers($search)
    {
        $this->authorsOptions = $this->makeOptions(
            (new WordPress($this->wordpressIntegration['url']))
                ->setUsername($this->wordpressIntegration['username'])
                ->setApplicationPassword($this->wordpressIntegration['app_password'])
                ->getCall('/wp-json/wp/v2/users?search=' . $search)
        );

        $this->authorsOptions = array_merge($this->authorsOptions, $this->savedAuthorOption);
    }

    public function searchTags($search)
    {
        $this->tagsOptions = $this->makeOptions(
            (new WordPress($this->wordpressIntegration['url']))
                ->setUsername($this->wordpressIntegration['username'])
                ->setApplicationPassword($this->wordpressIntegration['app_password'])
                ->getCall('/wp-json/wp/v2/tags?search=' . $search)
        );

        $this->tagsOptions = array_merge($this->tagsOptions, $this->savedTagsOption);
    }

    public function searchCategories($search)
    {
        $this->categoriesOptions = $this->makeOptions(
            (new WordPress($this->wordpressIntegration['url']))
                ->setUsername($this->wordpressIntegration['username'])
                ->setApplicationPassword($this->wordpressIntegration['app_password'])
                ->getCall('/wp-json/wp/v2/categories?search=' . $search)
        );

        $this->categoriesOptions = array_merge($this->categoriesOptions, $this->savedCategoriesOption);
    }

    private function validateWordpress()
    {
        $this->validate([
            'integration.name' => 'required|min:3|max:100',
            'wordpressIntegration.url' => 'required|max:300',
            'wordpressIntegration.username' => 'required|min:3|max:100',
            'wordpressIntegration.app_password' => 'required|max:100',
            'wordpressIntegration.status' => 'required',
            'wordpressIntegration.author' => 'required|in:' . implode(',', array_column($this->authorsOptions, 'id')),
            'wordpressIntegration.time_gap' => 'required',
        ], [
            'wordpressIntegration.url.required' => 'The URL field is required.',
            'wordpressIntegration.url.max' => 'The URL field may not be greater than 300 characters.',
            'wordpressIntegration.username.required' => 'The username field is required.',
            'wordpressIntegration.username.min' => 'The username field must be at least 3 characters.',
            'wordpressIntegration.username.max' => 'The username field may not be greater than 100 characters.',
            'wordpressIntegration.app_password.required' => 'The app password field is required.',
            'wordpressIntegration.app_password.max' => 'The app password field may not be greater than 100 characters.',
            'wordpressIntegration.status.required' => 'The status field is required.',
            'wordpressIntegration.author.required' => 'The author field is required.',
            'wordpressIntegration.time_gap.required' => 'The time gap field is required.',
        ]);
    }

    private function validateShopify()
    {
        $this->validate([
            'integration.name' => 'required|min:3|max:100',
            'shopifyIntegration.shop_name' => 'required|max:300',
            'shopifyIntegration.access_token' => 'required|max:100',
            'shopifyIntegration.author' => 'required|in:' . implode(',', array_column($this->shopifyAuthors, 'id')),
            'shopifyIntegration.blog' => 'required|in:' . implode(',', array_column($this->shopifyBlogs, 'id')),
        ], [
            'shopifyIntegration.shop_name.required' => 'The shop name field is required.',
            'shopifyIntegration.shop_name.max' => 'The shop name field may not be greater than 300 characters.',
            'shopifyIntegration.access_token.required' => 'The access token field is required.',
            'shopifyIntegration.access_token.max' => 'The access token field may not be greater than 100 characters.',
            'shopifyIntegration.author.required' => 'The author field is required.',
            'shopifyIntegration.blog.required' => 'The blog field is required.',
        ]);
    }

    public function saveWordPress()
    {
        $this->validateWordpress();
        $details = $this->prepareWordPressDetails();

        if ($this->action == 'create') {
            $this->createWordPressIntegration($details);
        } else if ($this->action == 'edit') {
            $this->updateWordPressIntegration($details);
        }

        return redirect()->route('integrations');
    }

    private function prepareWordPressDetails()
    {
        return [
            'url' => $this->wordpressIntegration['url'],
            'username' => $this->wordpressIntegration['username'],
            'app_password' => $this->wordpressIntegration['app_password'],
            'status' => $this->wordpressIntegration['status'],
            'categories' => json_encode($this->wordpressIntegration['categories']),
            'tags' => json_encode($this->wordpressIntegration['tags']),
            'author' => (int) $this->wordpressIntegration['author'],
            'time_gap' => $this->wordpressIntegration['time_gap'],
        ];
    }

    private function createWordPressIntegration($details)
    {
        DB::transaction(function () use ($details) {
            $integration = Integration::create([
                "id" => Str::uuid(),
                "name" => $this->integration['name'],
                "integration_type_id" => IntegrationType::firstWhere('name', IntegrationTypeEnum::WORDPRESS->value)->id,
                "user_id" => auth()->user()->id,
            ]);
            WordpressIntegration::create(array_merge([
                "integration_id" => $integration->id,
            ], $details));
        });
    }

    private function updateWordPressIntegration($details)
    {
        DB::transaction(function () use ($details) {
            Integration::updateOrCreate(['id' => $this->integrationId], ["name" => $this->integration['name']]);
            WordpressIntegration::updateOrCreate(['id' => $this->wordpressIntegrationId], $details);
        });
    }

    public function saveShopify()
    {
        $this->validateShopify();
        $details = $this->prepareShopifyDetails();

        if ($this->action == 'create') {
            $this->createShopifyIntegration($details);
        } else if ($this->action == 'edit') {
            $this->updateShopifyIntegration($details);
        }

        return redirect()->route('integrations');
    }

    private function prepareShopifyDetails()
    {
        return [
            'shop_name' => $this->shopifyIntegration['shop_name'],
            'access_token' => $this->shopifyIntegration['access_token'],
            'blog' => (int) $this->shopifyIntegration['blog'],
            'author' => $this->shopifyIntegration['author'],
        ];
    }

    private function createShopifyIntegration($details)
    {
        DB::transaction(function () use ($details) {
            $integration = Integration::create([
                "id" => Str::uuid(),
                "name" => $this->integration['name'],
                "integration_type_id" => IntegrationType::firstWhere('name', IntegrationTypeEnum::SHOPIFY->value)->id,
                "user_id" => auth()->user()->id,
            ]);

            ShopifyIntegration::create(array_merge([
                "integration_id" => $integration->id->toString(),
            ], $details));
        });
    }

    private function updateShopifyIntegration($details)
    {
        DB::transaction(function () use ($details) {
            Integration::updateOrCreate(['id' => $this->integrationId], ["name" => $this->integration['name']]);
            ShopifyIntegration::updateOrCreate(['id' => $this->shopifyIntegrationId], $details);
        });
    }


    public function mount()
    {
        // DB::enableQueryLog();
        // $result = IntegrationType::where('name', IntegrationTypeEnum::WORDPRESS->value)->pluck('id');
        // $query = DB::getQueryLog(); dd($query, $result);

        $this->action = Route::currentRouteName() === "integration.create" ? "create" : "edit";

        // TESTING ONLY
        // $this->updateWordPressInfo();
        $this->updateShopifyInfo();

        if ($this->action === 'edit') {
            $this->integrationId = Route::current()->parameter('id');
            $integration = Integration::where('id', $this->integrationId)->first();
            $this->integration = [
                'name' => $integration->name,
                'integration_type_id' => $integration->integration_type_id,
            ];

            switch ($integration->integrationType->name) {
                case IntegrationTypeEnum::WORDPRESS->value:
                    $this->selectedTab = IntegrationTypeEnum::WORDPRESS->value;
                    $wordpressIntegration = $integration->wordpressIntegration()->first();
                    $this->wordpressIntegrationId = $wordpressIntegration->id;
                    $this->wordpressIntegration = [
                        "url" => $wordpressIntegration->url,
                        "username" => $wordpressIntegration->username,
                        "app_password" => $wordpressIntegration->app_password,
                        "status" => $wordpressIntegration->status,
                        "categories" => json_decode($wordpressIntegration->categories, true),
                        "tags" => json_decode($wordpressIntegration->tags, true),
                        "author" => $wordpressIntegration->author,
                        "time_gap" => $wordpressIntegration->time_gap,
                    ];
                    $this->updateWordPressInfo();
                    break;
                case IntegrationTypeEnum::SHOPIFY->value:
                    $this->selectedTab = IntegrationTypeEnum::SHOPIFY->value;
                    $shopifyIntegration = $integration->shopifyIntegration()->first();
                    $this->shopifyIntegrationId = $shopifyIntegration->id;
                    $this->shopifyIntegration = [
                        "shop_name" => $shopifyIntegration->shop_name,
                        "access_token" => $shopifyIntegration->access_token,
                        "blog" => $shopifyIntegration->blog,
                        "author" => $shopifyIntegration->author,
                    ];
                    $this->updateShopifyInfo();
                    break;
                default:
                    break;
            }
        }
    }

    public function render()
    {
        return view('livewire.integration-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
