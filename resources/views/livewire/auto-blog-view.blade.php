<div>

    <x-header class="" size="text-xl font-[700]"
        subtitle="Create a new AutoBlog to automatically generate & publish articles to your website."
        title="AutoBlogs / {{ $action == 'create' ? 'Create' : $autoBlog['name'] }}" />


    <x-form wire:submit="save">
        <div
            class="flex items-center justify-between rounded-md bg-[#c8eafe] px-4 py-3">
            <div class="flex items-center justify-center">
                <x-icon class="mr-3 w-6 text-[#727272]"
                    name="phosphor.warning-circle-fill" />
                <div>You must upgrade to <strong>AutoBlog</strong> to use
                    AutoBlogs.</div>
            </div>
            <x-button class="bg-neutral-900 font-semibold  hover:bg-gray-700 btn-sm text-white"
                icon-right="o-arrow-small-right" label="Subscription" />
        </div>
        <div class="mb-4">
            <x-input label="Name" maxlength="100"
                placeholder="Weekly Cake Recipes" wire:model="autoBlog.name" />
        </div>

        @php
            $presetOptions = array_merge([['id' => 0, 'name' => 'Please select a preset.', 'disabled' => true]], $presets);
        @endphp
        <div class="mb-4">
            <div class="mb-2 flex items-center justify-between">
                <div class="mr-3 font-medium">Preset</div>
                <x-button class=" border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900 btn-xs" icon="bi.plus"
                    label="Create Preset" link="{{ route('preset.create') }}" />
            </div>
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
            <div class="mb-2 flex items-center justify-between">
                <div class="mr-3 font-medium">Integration</div>
                <x-button class=" border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900 btn-xs" icon="bi.plus"
                    label="Create Preset"
                    link="{{ route('integration.create') }}" />
            </div>
            <x-select :options="$integrationOptions" class="text-base"
                wire:model="autoBlog.integration_id" />
        </div>

        <div class="mb-4 w-fit">
            <div class="mb-2 mr-3 font-medium">Status</div>
            <x-custom-toggle :enabled="$autoBlog['status']" :label="$autoBlog['status'] ? 'Active' : 'Paused'"
                class="text-base checked:bg-neutral-900" wire:model.change="autoBlog.status" />
            @if ($autoBlog['status'])
                <div class="mt-2 text-sm">
                    Next articles will be generated @
                    <strong>immediately.</strong>
                </div>
            @endif
        </div>


        <div class="grid w-full grid-cols-2 gap-5">
            <x-button
                class=" border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900 w-full text-base"
                label="Cancel" link="{{ route('autoblogs') }}" />
            <x-button :label="$action === 'create' ? 'Create New AutoBlog' : 'Save'"
                class="bg-neutral-900 font-semibold  hover:bg-gray-700 text-white w-full text-base"
                type="submit" wire:loading.attr="disabled" />
        </div>
    </x-form>
</div>
