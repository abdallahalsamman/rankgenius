<div>
    <div class="flex justify-between mt-10">
    <x-header size="text-xl font-[700]" title="Batch" />
    <div>
        <x-button
            class="font-semibold text-white btn-sm bg-neutral-900 hover:bg-gray-700"
            icon="o-plus-small" label="Generate More"
            link="{{ route('dashboard') }}" />
        </div>
    </div>
    <div class="grid grid-row-4">
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
                    <span class="ml-3 loading loading-spinner loading-xs"></span>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="flex w-full items-center border-b-[1px] border-base-200 px-6 py-3">
            <div class="w-1/2 text-sm font-bold text-gray-600">
                {{ $batch->mode }}</div>
            <div class="w-1/2">
                {{ $batch->details }}
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
            <div class="flex justify-between items-center w-1/2">
                <div
                @if ($batch->status === \App\Enums\BatchStatusEnum::IN_PROGRESS->value) wire:poll.5s @endif
                >
                    {{ $batch->articles->count() }} / {{ $batch->quantity }}
                </div>
                <div>
                    {{-- <x-button :disabled="$batch->articles->count() === 0"
                        class="font-semibold text-white btn-sm bg-neutral-900 hover:bg-gray-700"
                        icon="s-eye" label="View"
                        wire:click="viewBatch('{{ $batch->id }}')" /> --}}
                    {{-- <x-button :disabled="$batch->articles->count() === 0"
                        class="ml-1 font-semibold text-white btn-outline btn-sm bg-neutral-900 hover:bg-gray-700"
                        icon="phosphor.download-simple-bold" label=".zip" /> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between mt-10">
        <div class="flex gap-2">
            {{-- <x-select :options="$integrationOptions" class="select-sm"
                wire:model="integration_id" />
            <x-button :disabled="empty($integrationOptions)" :disabled="$batch->articles->count() === 0"
                class="font-semibold text-white btn-sm bg-neutral-900 hover:bg-gray-700 disabled:text-white"
                label="Publish all to Integration"
                wire:click="publishBatchToIntegration" /> --}}
        </div>
    </div>
    @if ($batch->articles->count() > 0)
    <div class="flex relative flex-col h-full">
        <div class="bg-white">
            <div class="flex gap-5 items-center px-6 py-4"
                wire:loading.class="opacity-50">
                <x-button :disabled="$selectedArticleIdx === 0"
                    class="bg-white btn-sm text-neutral-900 hover:border-black hover:bg-neutral-900 hover:text-white"
                    icon="o-arrow-small-left" label="Previous" spinner
                    wire:click="previous" />

                <div class="w-full">
                    <x-select :options="$batch->articles->toArray()" class="pr-8 btn-sm"
                        option_label="title"
                        wire:model.change="selectedArticleId" />
                </div>

                <x-button :disabled="$selectedArticleIdx ==
                    $batch->articles->count() - 1"
                    class="bg-white btn-sm text-neutral-900 hover:border-black hover:bg-neutral-900 hover:text-white"
                    icon-right="o-arrow-small-right" label="Next" spinner
                    wire:click="next" />
                {{-- <x-button @click="$wire.batchModal = false"
                    class="btn-sm border-0 bg-transparent px-[6px]"
                    icon="bi.x-lg" /> --}}
            </div>
        </div>
        <livewire:article-view :selectedArticle="$batch->articles[$selectedArticleIdx]" :key="$batch->articles[$selectedArticleIdx]->id" />
    </div>
    @endif
</div>

@vite(['resources/js/editor.js'])