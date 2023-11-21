<?php

namespace App\Livewire;

use App\Models\AutoBlog;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class AutoBlogView extends Component
{

    public $action, $autoBlogId, $presets = [], $integrations = [];
    public $quantity_allowed = [1, 2, 3, 5, 10, 20, 40, 60, 80, 100, 150, 300];
    public $autoBlog = [
        'name' => null,
        'quantity' => 1,
        'interval' => 24,
        'status' => false,
        'preset_id' => 0,
        'integration_id' => 0,
    ];

    public function mount()
    {
        $this->presets = auth()->user()->presets->toArray();
        $this->integrations = auth()->user()->integrations->toArray();
        $this->action = Route::currentRouteName() === "autoblog.create" ? "create" : "edit";

        if ($this->action === 'edit') {
            $this->autoBlogId = Route::current()->parameter('id');
            $autoBlog = AutoBlog::where('id', $this->autoBlogId)->first()->toArray();
            $this->autoBlog = [
                'name' => $autoBlog['name'],
                'quantity' => $autoBlog['quantity'],
                'interval' => $autoBlog['interval'],
                'status' => $autoBlog['status'],
                'preset_id' => $autoBlog['preset_id'],
                'integration_id' => $autoBlog['integration_id'],
            ];
        }
    }

    private function validateAutoBlog()
    {
        $this->validate([
            'autoBlog.name' => 'required|max:100',
            'autoBlog.quantity' => 'required|min:1|max:300',
            'autoBlog.interval' => 'required',
            'autoBlog.status' => 'required|bool',
            'autoBlog.preset_id' => 'required|exists:presets,id',
            'autoBlog.integration_id' => 'required|exists:integrations,id',
        ], [
            'autoBlog.name.required' => 'The name field is required.',
            'autoBlog.name.max' => 'The name may not be greater than :max characters.',
            'autoBlog.quantity.required' => 'The quantity field is required.',
            'autoBlog.quantity.min' => 'The quantity must be at least :min.',
            'autoBlog.quantity.max' => 'The quantity may not be greater than :max.',
            'autoBlog.interval.required' => 'The interval field is required.',
            'autoBlog.status.required' => 'The status field is required.',
            'autoBlog.status.bool' => 'The status field must be a boolean.',
            'autoBlog.preset_id.required' => 'The preset field is required.',
            'autoBlog.preset_id.exists' => 'The selected preset is invalid.',
            'autoBlog.integration_id.required' => 'The integration field is required.',
            'autoBlog.integration_id.exists' => 'The selected integration is invalid.',
        ]);
    }
    public function save()
    {
        $this->validateAutoBlog();

        $details = [
            'name' => $this->autoBlog['name'],
            'quantity' => $this->autoBlog['quantity'],
            'interval' => $this->autoBlog['interval'],
            'status' => $this->autoBlog['status'],
            'preset_id' => $this->autoBlog['preset_id'],
            'integration_id' => $this->autoBlog['integration_id'],
        ];

        if ($this->action == 'create') {
            AutoBlog::create(array_merge([
                'id' => Str::uuid(),
                'user_id' => auth()->user()->id,
            ], $details));

        } else if ($this->action == 'edit') {
            AutoBlog::updateOrCreate(['id' => $this->autoBlogId], $details);
        }

        return redirect()->route('autoblogs');

    }

    public function render()
    {
        return view('livewire.auto-blog-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
