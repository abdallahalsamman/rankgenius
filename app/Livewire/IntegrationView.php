<?php

namespace App\Livewire;

use Exception;
use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Integration;
use Illuminate\Support\Str;
use App\Models\IntegrationType;
use Illuminate\Support\Facades\DB;
use App\Models\WordpressIntegration;
use Illuminate\Support\Facades\Route;
use MadeITBelgium\WordPress\WordPress;
use Usernotnull\Toast\Concerns\WireToast;

class IntegrationView extends Component
{
    use WireToast;

    public $action, $integrationId, $wordpressIntegrationId;
    public $categoriesOptions = [], $tagsOptions = [], $statusesOptions = [], $authorsOptions = [];

    public $integration = [
        'name' => "",
    ];

    public $wordpressIntegration = [
        "url" => "https://luggagenboxes.com",
        "username" => "abbood",
        "app_password" => "cXCw10FGY87fX0Uh9e9JjNZ8",
        "status" => "publish",
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

                $this->authorsOptions = $this->makeOptions($authors);
                $this->tagsOptions = $this->makeOptions($tags);
                $this->statusesOptions = $this->makeOptions($statuses);
                $this->categoriesOptions = $this->makeOptions($categories);

                // Route::currentRouteName() on mount returns route name "integration.create"
                // While on /update returns "livewire.update"
                // we only want to set the author to default to the first author in authorsOptions
            } catch (Exception $e) {
                $errorMap = [
                    [
                        'match' => 'Could not resolve host: ',
                        'error' => $website . ' is not responding.',
                    ],
                    [
                        'match' => 'Unauthorized',
                        'error' => 'Invalid Username or Application Password.',
                    ],
                ];

                $error = $e->getMessage();
                foreach ($errorMap as $error) {
                    if (Str::contains($e->getMessage(), $error['match'])) {
                        $error = $error['error'];
                        break;
                    }
                }

                toast()
                    ->danger(strip_tags($error))
                    ->duration(3000)->push();
            }
        }
    }

    public function updated($key)
    {
        if (in_array($key, ['wordpressIntegration.url', 'wordpressIntegration.username', 'wordpressIntegration.app_password'])) {
            $this->updateWordPressInfo();
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

    public function mount()
    {
        // DB::enableQueryLog();
        // $result = IntegrationType::where('name', 'wordpress')->pluck('id');
        // $query = DB::getQueryLog(); dd($query, $result);

        $this->action = Route::currentRouteName() === "integration.create" ? "create" : "edit";

        // TESTING ONLY
        // $this->updateWordPressInfo();

        if ($this->action === 'edit') {
            $this->integrationId = Route::current()->parameter('id');
            $integration = Integration::where('id', $this->integrationId)->first();
            $this->integration = [
                'name' => $integration->name,
                'integration_type_id' => $integration->integration_type_id,
            ];
            if (IntegrationType::where('id', $integration['integration_type_id'])->value('name') == "wordpress") {
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
        }
    }

    private function validateWordpressIntegrationView()
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

    public function saveWordPress()
    {
        $this->validateWordpressIntegrationView();

        $details = [
            'url' => $this->wordpressIntegration['url'],
            'username' => $this->wordpressIntegration['username'],
            'app_password' => $this->wordpressIntegration['app_password'],
            'status' => $this->wordpressIntegration['status'],
            'categories' => json_encode($this->wordpressIntegration['categories']),
            'tags' => json_encode($this->wordpressIntegration['tags']),
            'author' => (int) $this->wordpressIntegration['author'],
            'time_gap' => $this->wordpressIntegration['time_gap'],
        ];

        if ($this->action == 'create') {
            DB::transaction(function () use ($details) {
                $integration = Integration::create([
                    "id" => Str::uuid(),
                    "name" => $this->integration['name'],
                    "integration_type_id" => IntegrationType::where('name', 'wordpress')->value('id'),
                    "user_id" => auth()->user()->id,
                ]);
                WordpressIntegration::create(array_merge([
                    "integration_id" => $integration->id,
                ], $details));
            });
        } else if ($this->action == 'edit') {
            DB::transaction(function () use ($details) {
                Integration::updateOrCreate(['id' => $this->integrationId], ["name" => $this->integration['name']]);
                WordpressIntegration::updateOrCreate(['id' => $this->wordpressIntegrationId], $details);
            });
        }

        return redirect()->route('integrations');
    }


    public function render()
    {
        return view('livewire.integration-view')->layout('layouts.dashboard');
    }
}
