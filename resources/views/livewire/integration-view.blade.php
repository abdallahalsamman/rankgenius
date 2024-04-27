<div>
    <x-header class="" size="text-xl font-[700]"
        subtitle="Create a new AutoBlog to automatically generate & publish articles to your website."
        title="AutoBlogs / {{ $action == 'create' ? 'Create' : $integration['name'] }}" />

    <div class="mb-10 flex items-center justify-between rounded-md bg-[#c8eafe] px-4 py-3">
        <div class="flex items-center justify-center">
            <x-icon class="mr-3 w-6 text-[#727272]" name="phosphor.warning-circle-fill" />
            <div>You must upgrade to <strong>AutoBlog</strong> to use
                Integrations.</div>
        </div>
        <x-button class="bg-neutral-900 font-semibold  hover:bg-gray-700 btn-sm text-white"
            icon-right="o-arrow-small-right" link="{{ route('pricing') }}" label="Subscription" />
    </div>

    <x-tabs selected="wordPress">
        {{-- Wordpress part --}}
        <x-tab icon="bi.wordpress" label="WordPress" name="wordPress">
            <div>
                <x-form wire:submit="saveWordPress">
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Integration Name
                        </div>
                        <x-input class="mb-2" maxlength="255" placeholder="My Wordpress Website" type="text"
                            wire:model="integration.name" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Wordpress URL
                        </div>
                        <x-input class="mb-2" hint="The URL where your Wordpress is installed." maxlength="255"
                            placeholder="https://mywebsite.com" :disabled="$action == 'edit'" type="text"
                            wire:model.live.debounce.1000ms="wordpressIntegration.url" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Username
                        </div>
                        <x-input class="mb-2"
                            hint="The username/email you use to login to your Wordpress. (Should be admin)"
                            maxlength="255" placeholder="admin@email.com" type="text" :disabled="$action == 'edit'"
                            wire:model.live.debounce.1000ms="wordpressIntegration.username" />
                    </div>
                    <div>
                        <div class="mb-2 mt-5 flex items-end justify-between">
                            <div class="mr-3 font-medium">Application
                                Password
                            </div>
                            <x-button
                                class="border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900 btn-xs"
                                icon-right="phosphor.youtube-logo-fill" label="Watch Tutorial"
                                link="{{ route('preset.create') }}" />
                        </div>
                        <x-input class="mb-2"
                            hint="This is not your normal password. This is a special Wordpress password for integrations. Watch the tutorial to learn more."
                            maxlength="255" placeholder="Pnaz HXK8 ZYZW oc8l 5J1l 6WxR" :disabled="$action == 'edit'"
                            type="text" wire:model.live.debounce.1000ms="wordpressIntegration.app_password" />
                    </div>

                    <div class="w-full py-2 text-center" wire:loading>
                        <span class="loading loading-dots loading-lg"></span>
                    </div>

                    @if (!empty($tagsOptions))
                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Author
                        </div>
                        <x-choices :options="$authorsOptions" single searchable search-function="searchUsers" class="text-base"
                            hint="Only admins are allowed to be authors." wire:model="wordpressIntegration.author" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Status
                        </div>
                        <x-select :options="$statusesOptions" class="text-base"
                            wire:model="wordpressIntegration.status" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Time gap
                            between
                            each post
                        </div>
                        @php
                        $timeGapOptions = [
                        [
                        'id' => 0,
                        'name' => 'No gap',
                        ],
                        [
                        'id' => 15,
                        'name' => '15 minutes',
                        ],
                        [
                        'id' => 60,
                        'name' => '1 hour',
                        ],
                        [
                        'id' => 60 * 4,
                        'name' => '4 hours',
                        ],
                        [
                        'id' => 60 * 24,
                        'name' => '1 day',
                        ],
                        [
                        'id' => 60 * 24 * 2,
                        'name' => '2 days',
                        ],
                        ];
                        @endphp
                        <x-select :options="$timeGapOptions" class="text-base"
                            wire:model="wordpressIntegration.time_gap" />
                    </div>

                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Categories
                        </div>
                        <x-choices :options="$categoriesOptions" {{-- search-function="searchMulti" searchable --}}
                            hint="Please select from your Categories"
                            no-result-text="Ops! Nothing here ..."
                            wire:model="wordpressIntegration.categories"
                            multiple
                            searchable
                            search-function="searchCategories" />
                    </div>

                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Tags
                        </div>
                        <x-choices :options="$tagsOptions" {{-- search-function="searchMulti" searchable --}}
                            hint="Please select from your Tags"
                            no-result-text="Ops! Nothing here ..."
                            wire:model="wordpressIntegration.tags"
                            multiple
                            searchable
                            search-function="searchTags" />
                    </div>
                    @endif
                    <div class="mt-5 grid w-full grid-cols-2 gap-5">
                        <x-button
                            class="border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900 w-full text-base"
                            label="Cancel" link="{{ route('integrations') }}" />
                        <x-button :disabled="empty($tagsOptions)"
                            :label="$action === 'create' ? 'Create New integration' : 'Save'"
                            class="bg-neutral-900 font-semibold  hover:bg-gray-700 text-white w-full text-base"
                            type="submit" wire:loading.attr="disabled" />
                    </div>
                </x-form>
            </div>
        </x-tab>

        {{-- Shopify part --}}
        {{-- <x-tab icon="fab.shopify" label="Shopify" name="shopify">
            <div>
                livewire autoformat vscode<x-form wire:submit="shopify">
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Integration Name
                        </div>
                        <x-input class="mb-2" maxlength="255"
                            placeholder="My Wordpress Website" type="text"
                            wire:model="preset.callToAction" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Shop Name
                        </div>
                        <x-input class="mb-2" maxlength="255"
                            placeholder="My Wordpress Website" type="text"
                            wire:model="wordpressIntegration.callToAction" />
                    </div>
                    <div>
                        <div class="mb-2 mt-5 flex items-end justify-between">
                            <div class="mr-3 font-medium">Application
                                Password
                            </div>
                            <x-button class="btn-primary btn-outline btn-xs"
                                icon-right="phosphor.youtube-logo-fill"
                                label="Watch Tutorial"
                                link="{{ route('preset.create') }}" />
</div>
<x-input class="mb-2" maxlength="255" placeholder="" type="text" wire:model="wordpressIntegration.app_password" />
</div>
<div>
    <div class="mb-2 mr-3 pt-5 font-medium">Blog
    </div>
    <x-choices :options="[]" no-result-text="Ops! Nothing here ..." search-function="searchMulti" searchable
        wire:model="users_multi_searchable_ids" />
</div>
<div>
    <div class="mb-2 mr-3 mt-5 font-medium">Author Name
    </div>
    <x-input class="mb-2" maxlength="255" placeholder="My Wordpress Website" type="text"
        wire:model="wordpressIntegration.author" />
</div>
<div class="mt-5 grid w-full grid-cols-2 gap-5">
    <x-button class="btn-primary btn-outline w-full text-base text-base-100" label="Cancel"
        link="{{ route('integrations') }}" />
    <x-button :label="$action === 'create' ? 'Create New integration' : 'Save'"
        class="btn-primary w-full text-base text-base-100" type="submit" wire:loading.attr="disabled" />
</div>
</x-form>
</div>
</x-tab> --}}
</x-tabs>
</div>