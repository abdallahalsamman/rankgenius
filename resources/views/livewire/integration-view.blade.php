<div>
    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="Create a new AutoBlog to automatically generate & publish articles to your website."
        title="AutoBlogs / {{ $action == 'create' ? 'Create' : $integration['name'] }}" />

    <x-tabs selected="wordPress">
        {{-- Wordpress part --}}
        <x-tab icon="bi.wordpress" label="WordPress" name="wordPress">
            <div>
                <x-form wire:submit="wordPress">
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Integration Name
                        </div>
                        <x-input class="mb-2" maxlength="255"
                            placeholder="My Wordpress Website" type="text"
                            wire:model="preset.callToAction" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Wordpress URL
                        </div>
                        <x-input class="mb-2"
                            hint="The URL where your Wordpress is installed."
                            maxlength="255" placeholder="https://mywebsite.com"
                            type="text" wire:model.change="wordpressIntegration.url" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 mt-5 font-medium">Username
                        </div>
                        <x-input class="mb-2"
                            hint="The username/email you use to login to your Wordpress. (Should be admin)"
                            maxlength="255" placeholder="admin@email.com"
                            type="text" wire:model.change="wordpressIntegration.username" />
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
                        <x-input class="mb-2"
                            hint="This is not your normal password. This is a special Wordpress password for integrations. Watch the tutorial to learn more."
                            maxlength="255"
                            placeholder="Pnaz HXK8 ZYZW oc8l 5J1l 6WxR"
                            type="text" wire:model.change="wordpressIntegration.app_password" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Status
                        </div>
                        <x-select :options="[]" class="text-base"
                            wire:model.change="preset.generationMode" />
                    </div>
                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Time gap between
                            each post
                        </div>
                        @php
                        $timeGapOptions = [
                            [
                            "id" => 0,
                            "name" => "No gap"
                            ],
                            [
                            "id" => 15,
                            "name" => "15 minutes"
                            ],
                            [
                            "id" => 60,
                            "name" => "1 hour"
                            ],
                            [
                            "id" => 60 * 4,
                            "name" => "4 hours"
                            ],
                            [
                            "id" => 60 * 24,
                            "name" => "1 day"
                            ],
                            [
                            "id" => 60 * 24 * 2,
                            "name" => "2 days"
                            ],
                        ];
                        @endphp
                        <x-select :options="$timeGapOptions" class="text-base"
                            wire:model.change="preset.generationMode" />
                    </div>

                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Categories
                        </div>
                        <x-choices :options="$categoriesOption"
                            no-result-text="Ops! Nothing here ..."
                            search-function="searchMulti" searchable
                            wire:model="users_multi_searchable_ids" />
                    </div>

                    <div>
                        <div class="mb-2 mr-3 pt-5 font-medium">Tags
                        </div>
                        <x-choices :options="$tagsOption"
                            no-result-text="Ops! Nothing here ..."
                            search-function="searchMulti" searchable
                            wire:model="users_multi_searchable_ids" />
                    </div>
                </x-form>
            </div>
        </x-tab>
        {{-- Shopify part --}}
        <x-tab icon="fab.shopify" label="Shopify" name="shopify">
            <div>
                <x-form wire:submit="shopify">

                </x-form>
            </div>
        </x-tab>
    </x-tabs>
</div>
