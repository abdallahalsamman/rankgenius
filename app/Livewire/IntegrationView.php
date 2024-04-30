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

    public $selectedTab = IntegrationTypeEnum::WORDPRESS->value;

    public $action, $integrationId;
    public $wordpressIntegrationId, $shopifyIntegrationId;
    public $categoriesOptions = [], $tagsOptions = [], $statusesOptions = [], $authorsOptions = [];
    public $savedAuthorOption = [], $savedCategoriesOption = [], $savedTagsOption = [];

    public $shopifyBlogs = [], $shopifyAuthors = [];
    public $shopifyIntegration = [
        // "shop_name" => "0ae717-b8",
        // "access_token" => "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",
        // "blog" => null,
        // "author" => null,

        "shop_name" => null,
        "access_token" => null,
        "blog" => null,
        "author" => null,
    ];

    public $integration = [
        'name' => "",
    ];

    public $wordpressIntegration = [
        // "url" => "https://www.aiobot.com/",
        // "username" => "sammanabdallah",
        // "app_password" => "UaoK qroT uPIC 7y4F I8GU PcGk",
        // "status" => "draft",
        // "categories" => [],
        // "tags" => [],
        // "author" => 1,
        // "time_gap" => 0,

        "url" => "",
        "username" => "",
        "app_password" => "",
        "status" => "publish",
        "categories" => [],
        "tags" => [],
        "author" => 1,
        "time_gap" => 0,
    ];

    private function initializeWordPressClient()
    {
        return (new WordPress($this->wordpressIntegration['url']))
            ->setUsername($this->wordpressIntegration['username'])
            ->setApplicationPassword($this->wordpressIntegration['app_password']);
    }

    public function getWordPressSavedInfo()
    {
        $wp = $this->initializeWordPressClient();

        $this->savedAuthorOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/users?include=' . $this->wordpressIntegration['author'])
        );

        $this->savedCategoriesOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/categories?include=' . implode(',', $this->wordpressIntegration['categories']))
        );

        $this->savedTagsOption = $this->makeOptions(
            $wp->getCall('/wp-json/wp/v2/tags?include=' . implode(',', $this->wordpressIntegration['tags']))
        );
    }

    public function updateShopifyInfo()
    {
        $this->shopifyAuthors = [];
        $this->shopifyBlogs = [];

        if (!empty($this->shopifyIntegration['shop_name']) && !empty($this->shopifyIntegration['access_token'])) {
            try {
                $shopify = new Shopify($this->shopifyIntegration['access_token'], $this->shopifyIntegration['shop_name'] . '.myshopify.com', '2024-04');
                $shopify->getBlogsCount();
                toast()->success('Logged in successfully to Shopify')->push();

                $this->shopifyBlogs = $shopify->getBlogs()->toArray();
                $shopifyAuthors = $shopify->getArticleAuthors();
                $this->shopifyAuthors = array_map(fn ($name) => ['id' => $name, 'name' => $name], $shopifyAuthors);

                if ($this->action == 'create') {
                    $this->shopifyIntegration['author'] = $shopifyAuthors[0];
                    $this->shopifyIntegration['blog'] = $this->shopifyBlogs[0]['id'];
                }
            } catch (Exception $e) {
                Log::error($e);
                toast()->danger(strip_tags($e->getMessage()))->duration(3000)->push();
            }
        }
    }

    public function updateWordPressInfo()
    {
        $this->resetOptions();
        $wp = $this->initializeWordPressClient();

        if ($this->validWordPressCredentials()) {
            try {
                $this->performWordPressAuthTest($wp);

                $this->authorsOptions = $this->makeOptions($wp->getCall('/wp-json/wp/v2/users?per_page=100'));
                $this->tagsOptions = $this->makeOptions($wp->getCall('/wp-json/wp/v2/tags?per_page=100'));
                $this->statusesOptions = $this->makeOptions($wp->getCall('/wp-json/wp/v2/statuses?per_page=100'));
                $this->categoriesOptions = $this->makeOptions($wp->getCall('/wp-json/wp/v2/categories?per_page=100'));

                toast()->success('Logged in successfully')->push();

                if ($this->action == 'edit') {
                    $this->getWordPressSavedInfo();
                    $this->mergeSavedOptions();
                }
            } catch (Exception $e) {
                $this->handleWordPressError($e);
            }
        }
    }

    private function resetOptions()
    {
        $this->tagsOptions = $this->statusesOptions = $this->categoriesOptions = $this->authorsOptions = [];
    }

    private function validWordPressCredentials()
    {
        return !empty($this->wordpressIntegration['url']) && !empty($this->wordpressIntegration['username']) && !empty($this->wordpressIntegration['app_password']);
    }

    private function performWordPressAuthTest($wp)
    {
        $tag = $wp->postCall('/wp-json/wp/v2/tags', ['name' => env('APP_NAME') . '_test']);
        $wp->deleteCall('/wp-json/wp/v2/tags/' . $tag->id . '?force=true');
    }

    private function mergeSavedOptions()
    {
        $this->authorsOptions = array_merge($this->authorsOptions, $this->savedAuthorOption);
        $this->tagsOptions = array_merge($this->tagsOptions, $this->savedTagsOption);
        $this->categoriesOptions = array_merge($this->categoriesOptions, $this->savedCategoriesOption);

        // remove duplicates
        $this->authorsOptions = array_unique($this->authorsOptions, SORT_REGULAR);
        $this->tagsOptions = array_unique($this->tagsOptions, SORT_REGULAR);
        $this->categoriesOptions = array_unique($this->categoriesOptions, SORT_REGULAR);
    }

    private function handleWordPressError($e)
    {
        Log::error($e);
        $error_text = $this->parseWordPressError($e->getMessage());
        toast()->danger(strip_tags($error_text))->duration(3000)->push();
    }

    private function parseWordPressError($message)
    {
        $errorMap = [
            ['match' => 'Could not resolve host: ', 'text' => $this->wordpressIntegration['url'] . ' is not responding.'],
            ['match' => 'Unauthorized', 'text' => 'Invalid Username or Application Password.'],
        ];

        foreach ($errorMap as $error) {
            if (Str::contains($message, $error['match'])) {
                return $error['text'];
            }
        }

        return $message;
    }

    public function searchUsers($search)
    {
        $this->authorsOptions = $this->makeOptions(
            $this->initializeWordPressClient()->getCall('/wp-json/wp/v2/users?search=' . $search)
        );
        $this->mergeSavedOptions();
    }

    public function searchTags($search)
    {
        $this->tagsOptions = $this->makeOptions(
            $this->initializeWordPressClient()->getCall('/wp-json/wp/v2/tags?search=' . $search)
        );
        $this->mergeSavedOptions();
    }

    public function searchCategories($search)
    {
        $this->categoriesOptions = $this->makeOptions(
            $this->initializeWordPressClient()->getCall('/wp-json/wp/v2/categories?search=' . $search)
        );
        $this->mergeSavedOptions();
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
        ], [], [
            'integration.name' => 'Integration Name',
            'wordpressIntegration.url' => 'WordPress URL',
            'wordpressIntegration.username' => 'Username',
            'wordpressIntegration.app_password' => 'Application Password',
            'wordpressIntegration.status' => 'Status',
            'wordpressIntegration.author' => 'Author',
            'wordpressIntegration.time_gap' => 'Time Gap',
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
        ], [], [
            'integration.name' => 'Integration Name',
            'shopifyIntegration.shop_name' => 'Shop Name',
            'shopifyIntegration.access_token' => 'Access Token',
            'shopifyIntegration.author' => 'Author',
            'shopifyIntegration.blog' => 'Blog',
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

    private function loadIntegrationData()
    {
        $this->integrationId = Route::current()->parameter('id');
        $integration = Integration::where('id', $this->integrationId)->first();
        $this->integration = [
            'name' => $integration->name,
            'integration_type_id' => $integration->integration_type_id,
        ];

        switch ($integration->integrationType->name) {
            case IntegrationTypeEnum::WORDPRESS->value:
                $this->loadWordPressIntegration($integration);
                break;
            case IntegrationTypeEnum::SHOPIFY->value:
                $this->loadShopifyIntegration($integration);
                break;
            default:
                break;
        }
    }

    private function loadWordPressIntegration($integration)
    {
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
    }

    private function loadShopifyIntegration($integration)
    {
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
    }

    public function mount()
    {
        $this->action = Route::currentRouteName() === "integration.create" ? "create" : "edit";

        if ($this->action === 'edit') {
            $this->loadIntegrationData();
        }
    }

    public function render()
    {
        return view('livewire.integration-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
