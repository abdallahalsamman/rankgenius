<div>
    <x-header size="text-xl font-[700]" title="Batch" />
    <div class="grid-row-4 grid">
        <div
            class="flex w-full items-center border-b-[1px] border-base-200 px-6 py-3">
            <div class="w-1/2 text-sm font-bold text-gray-600">STATUS</div>
            <div class="w-1/2">
                @php
                    $statusColor = [
                        \App\Enums\BatchStatusEnum::IN_PROGRESS->value => 'bg-blue-200 text-info-content',
                        \App\Enums\BatchStatusEnum::DONE->value => 'bg-green-200 text-success-content',
                        \App\Enums\BatchStatusEnum::CANCELLED->value => 'bg-red-200 text-error-content',
                    ];
                @endphp
                <div class="flex items-center">
                    <div class="{{ $statusColor[$batch->status] }} w-fit rounded px-2 py-1 text-sm font-medium"
                        @if ($batch->status === \App\Enums\BatchStatusEnum::IN_PROGRESS->value) wire:poll.5s @endif
                        >
                        {{ Str::title($batch->status) }}
                    </div>
                    @if ($batch->status === \App\Enums\BatchStatusEnum::IN_PROGRESS->value)
                    <span class="loading ml-3 loading-spinner loading-xs"></span>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="flex w-full items-center border-b-[1px] border-base-200 px-6 py-3">
            <div class="w-1/2 text-sm font-bold text-gray-600">
                {{ $batch->mode }}</div>
            <div class="w-1/2">
                <div class="whitespace-pre-line break-words">
                    {{ $batch->details }}</div>
            </div>
        </div>

        <div
            class="flex w-full items-center border-b-[1px] border-base-200 px-6 py-3">
            <div class="w-1/2 text-sm font-bold text-gray-600">LANGUAGE</div>
            <div class="w-1/2">
                {{ $batch->language }}
            </div>
        </div>

        <div
            class="flex w-full items-center border-b-[1px] border-base-200 px-6 py-3">
            <div class="w-1/2 text-sm font-bold text-gray-600">ARTICLES</div>
            <div class="flex w-1/2 items-center justify-between">
                <div
                @if ($batch->status === \App\Enums\BatchStatusEnum::IN_PROGRESS->value) wire:poll.5s @endif
                >
                    {{ $batch->articles->count() }} / {{ $batch->quantity }}
                </div>
                <div>
                    <x-button :disabled="$batch->articles->count() === 0"
                        class="btn-sm bg-neutral-900 font-semibold text-white hover:bg-gray-700"
                        icon="s-eye" label="View"
                        wire:click="viewBatch('{{ $batch->id }}')" />
                    <x-button :disabled="$batch->articles->count() === 0"
                        class="btn-outline btn-sm ml-1 bg-neutral-900 font-semibold text-white hover:bg-gray-700"
                        icon="phosphor.download-simple-bold" label=".zip" />
                </div>
            </div>
        </div>

    </div>

    <div class="mt-10 flex justify-between">
        <div class="flex gap-2">
            <x-select :options="$integrationOptions" class="select-sm"
                wire:model="integration_id" />
            <x-button :disabled="empty($integrationOptions)" :disabled="$batch->articles->count() === 0"
                class="btn-sm bg-neutral-900 font-semibold text-white hover:bg-gray-700 disabled:text-white"
                label="Publish all to Integration"
                wire:click="publishBatchToIntegration" />
        </div>
        <div>
            <x-button
                class="btn-sm bg-neutral-900 font-semibold text-white hover:bg-gray-700"
                icon="o-plus-small" label="Generate More"
                link="{{ route('dashboard') }}" />
        </div>
    </div>
    <x-modal class="popUpMessage" wire:model="batchModal">
        <div class="relative flex h-full flex-col">
            <div class="bg-white">
                <div class="flex items-center gap-5 px-6 py-4"
                    wire:loading.class="opacity-50">
                    <x-button :disabled="$selectedArticleIdx === 0"
                        class="btn-sm bg-white text-neutral-900 hover:border-black hover:bg-neutral-900 hover:text-white"
                        icon="o-arrow-small-left" label="Previous" spinner
                        wire:click="previous" />

                    <div class="w-full">
                        <x-select :options="$batch->articles->toArray()" class="btn-sm pr-8"
                            option_label="title"
                            wire:model.change="selectedArticleId" />
                    </div>

                    <x-button :disabled="$selectedArticleIdx ==
                        $batch->articles->count() - 1"
                        class="btn-sm bg-white text-neutral-900 hover:border-black hover:bg-neutral-900 hover:text-white"
                        icon-right="o-arrow-small-right" label="Next" spinner
                        wire:click="next" />
                    <x-button @click="$wire.batchModal = false"
                        class="btn-sm border-0 bg-transparent px-[6px]"
                        icon="bi.x-lg" />
                </div>
            </div>
            @if ($selectedArticle)
                <x-article-view :selectedArticle="$selectedArticle" />
            @endif
        </div>
    </x-modal>
</div>
