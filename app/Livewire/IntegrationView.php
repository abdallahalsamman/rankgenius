<?php

namespace App\Livewire;

use Exception;
use Mary\Traits\Toast;
use Livewire\Component;
use Illuminate\Support\Str;
use MadeITBelgium\WordPress\WordPress;

class IntegrationView extends Component
{
    use Toast;

    public $action;
    public $categoriesOptions = [], $tagsOptions = [], $statusesOptions = [], $authorsOptions = [];

    public $integration = [
        'name' => null,
        'integration_type_id' => 1,
        'user_id' => 0,
    ];

    public $wordpressIntegration = [
        "url" => "https://luggagenboxes.com/?",
        "username" => "abbood",
        "app_password" => "cXCw10FGY87fX0Uh9e9JjNZ8",
        "status" => null,
        "categories" => [],
        "tags" => [],
        "author" => null,
        "time_gap" => "",
    ];

    public function updateWordPressInfo()
    {
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

                $this->toast('success', 'Logged in to WordPress successfully', position: 'toast-top toast-end');
                $this->tagsOptions = $this->makeOptions($tags);
                $this->statusesOptions = $this->makeOptions($statuses);
                $this->categoriesOptions = $this->makeOptions($categories);
                $this->authorsOptions = $this->makeOptions($authors);
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

                return $this->toast(
                    type: 'error',
                    title: strip_tags($error),
                    description: null,
                    position: 'toast-top toast-end',
                    timeout: 3000,
                    redirectTo: null
                );
            }

        }
    }

    public function updated()
    {
        $this->updateWordPressInfo();
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
        // $this->updateWordPressInfo();
        // $this->action = Route::currentRouteName() === "integration.create" ? "create" : "edit";

        // if ($this->action === 'edit') {
        //     $this->autoBlogId = Route::current()->parameter('id');
        //     $autoBlog = AutoBlog::where('id', $this->autoBlogId)->first()->toArray();
        //     $this->autoBlog = [
        //         'name' => $autoBlog['name'],
        //         'quantity' => $autoBlog['quantity'],
        //         'interval' => $autoBlog['interval'],
        //         'status' => $autoBlog['status'],
        //         'preset_id' => $autoBlog['preset_id'],
        //         'integration_id' => $autoBlog['integration_id'],
        //     ];
        // }
    }

    public function render()
    {
        return view('livewire.integration-view')->layout('layouts.dashboard');
    }
}
