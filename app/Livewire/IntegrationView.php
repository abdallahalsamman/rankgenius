<?php

namespace App\Livewire;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Exceptions\ToastException;
use App\Services\WordPressService;
use Illuminate\Support\Facades\Route;

class IntegrationView extends Component
{
    use Toast;

    public $action, $categoriesOption = [], $tagsOption = [];

    public $integration = [
        'name' => null,
        'integration_type_id' => 1,
        'user_id' => 0,
    ];

    public $wordpressIntegration = [
        "url" => "https://luggagenboxes.com/?",
        "username" => "abboo",
        "app_password" => "cXCw10FGY87fX0Uh9e9JjNZ8",
        "status" => "",
        "categories" => "",
        "tags" => "",
        "time_gap" => "",
    ];

    public function updated($key, $value, WordPressService $wordPressService) {
        $website = $this->wordpressIntegration['url'];
        $username = $this->wordpressIntegration['username'];
        $app_password = $this->wordpressIntegration['app_password'];
        $app_password = str_replace(' ', '', $app_password);

        if (!empty($website) && !empty($username) && !empty($app_password)) {
            try {
                $tags = $wordPressService->getTags($website, $username, $app_password);
                $statuses = $wordPressService->getStatuses($website, $username, $app_password);
                $categories = $wordPressService->getCategories($website, $username, $app_password);
                $authors = $wordPressService->getAuthors($website, $username, $app_password);


                $this->toast(
                    type: 'success',
                    title: 'Logged in to WordPress successfully',
                    description: null,
                    position: 'toast-top toast-end',
                    timeout: 3000,
                    redirectTo: null
                );

            } catch (ToastException $e) {
                $this->toast(
                    type: 'error',
                    title: strip_tags($e->getMessage()),
                    description: null,
                    position: 'toast-top toast-end',
                    timeout: 3000,
                    redirectTo: null
                );
            }

        }
    }

    public function mount()
    {
        // $this->integrations = auth()->user()->integrations->toArray();
        $this->action = Route::currentRouteName() === "integration.create" ? "create" : "edit";

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
