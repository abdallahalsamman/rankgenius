<div>

    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="Create a new AutoBlog to automatically generate & publish articles to your website."
        title="AutoBlogs / {{ $action == 'create' ? 'Create' : $autoBlog['name'] }}" />


    <x-form wire:submit="save">
        <div
            class="flex items-center justify-between rounded-md bg-[#feebc8] px-4 py-3">
            <div class="flex items-center justify-center">
                <x-icon class="mr-3 w-6 text-[#dd6b20]"
                    name="phosphor.warning-circle-fill" />
                <div>You must upgrade to <strong>AutoBlog</strong> to use
                    AutoBlogs.</div>
            </div>
            <x-button class="btn-primary btn-sm text-white" icon-right="o-arrow-small-right"
                label="Subscription" />
        </div>
        <div class="mb-4">
            <x-input label="Name" maxlength="100"
                placeholder="Weekly Cake Recipes" wire:model="autoBlog.name" />
        </div>

        @php
            $presetOptions = array_merge([['id' => 0, 'name' => 'Please select a preset.', 'disabled' => true]], $presets);
        @endphp
        <div class="mb-4">
            <div class="mb-2 mr-3 font-medium">Preset</div>
            <x-select :options="$presetOptions" class="text-base"
                wire:model="autoBlog.preset_id" />
        </div>

        <div class="mb-4">
            @php
                $intervals = [
                    [
                        'id' => 30 * 24,
                        'name' => 'Every Month',
                    ],
                    [
                        'id' => 7 * 24,
                        'name' => 'Every Week',
                    ],
                    [
                        'id' => 24,
                        'name' => 'Every Day',
                    ],
                    [
                        'id' => 12,
                        'name' => 'Every 12 Hours',
                    ],
                    [
                        'id' => 6,
                        'name' => 'Every 6 Hours',
                    ],
                ];

                $article_counts = [];
                foreach ($quantity_allowed as $i) {
                    $article_counts[] = ['id' => $i, 'name' => $i . ' ' . Str::plural('Article', $i)];
                }
            @endphp
            <div class="mb-2 mr-3 font-medium">Quantity</div>
            <div class="flex w-full gap-5">
                <div class="w-full">
                    <x-select :options="$article_counts" class="text-base"
                        wire:model="autoBlog.quantity" />
                </div>
                <div class="w-full">
                    <x-select :options="$intervals" class="text-base"
                        wire:model="autoBlog.interval" />
                </div>

            </div>
        </div>

        @php
            $integrationOptions = array_merge([['id' => 0, 'name' => 'Please select a preset.', 'disabled' => true]], $integrations);
        @endphp
        <div class="mb-4">
            <div class="mb-2 mr-3 font-medium">Integration</div>
            <x-select :options="$integrationOptions" class="text-base"
                wire:model="autoBlog.integration_id" />
        </div>

        <div class="mb-4">
            <div class="mb-2 mr-3 font-medium">Status</div>
            <x-custom-toggle :enabled="$autoBlog['status']" :label="$autoBlog['status'] ? 'Active' : 'Paused'"
                class="text-base" wire:model.change="autoBlog.status" />
            @if ($autoBlog['status'])
                <div class="mt-2 text-sm">
                    Next articles will be generated @
                    <strong>immediately.</strong>
                </div>
            @endif
        </div>


        <div class="grid w-full grid-cols-2 gap-5">
            <x-button
                class="btn-primary btn-outline w-full text-base text-base-100"
                label="Cancel" link="{{ route('autoblogs') }}" />
            <x-button :label="$action === 'create' ? 'Create New AutoBlog' : 'Save'"
                class="btn-primary w-full text-base text-base-100"
                type="submit" wire:loading.attr="disabled" />
        </div>
    </x-form>
</div>
