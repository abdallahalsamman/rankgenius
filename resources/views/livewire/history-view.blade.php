<div>
    <x-header size="text-xl font-[700]" title="Batch" />
    <div class="grid grid-row-4">
        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">STATUS</div>
            <div class="w-1/2">
                @php
                    $statusColor = [
                        \App\Enums\BatchStatusEnum::IN_PROGRESS->value => 'bg-blue-100 text-info-content',
                        \App\Enums\BatchStatusEnum::DONE->value => 'bg-green-100 text-success-content',
                        \App\Enums\BatchStatusEnum::CANCELLED->value => 'bg-red-100 text-error-content',
                    ];
                @endphp
                <div class="{{ $statusColor[$batch->status] }} rounded text-sm w-fit font-medium py-1 px-2">
                    {{ Str::title($batch->status) }}</div>
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3 border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">{{ $batch->mode }}</div>
            <div class="w-1/2">
                <div class="whitespace-pre-line break-words">{{ $batch->details }}</div>
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">LANGUAGE</div>
            <div class="w-1/2">
                {{ $batch->language }}
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">ARTICLES</div>
            <div class="w-1/2 flex justify-between items-center">
                <div>
                    {{ $batch->articles->count() }} / {{ $batch->quantity }}
                </div>
                <div>
                    <x-button icon="s-eye" label="View" class="btn-primary text-base-100 btn-sm" wire:click="viewBatch('{{ $batch->id }}')" />
                    <x-button icon="phosphor.download-simple-bold" label=".zip"
                        class="btn-primary btn-outline btn-sm ml-1" />
                </div>
            </div>
        </div>

    </div>

    <div class="mt-10 flex justify-between">
        <div class="flex gap-2">
            <x-select class="select-sm" />
            <x-button label="Publish all to Integration" class="btn-primary text-base-100 btn-sm" />
        </div>
        <div>
            <x-button icon="o-plus-small" label="Generate More" link="{{ route('dashboard') }}" class="btn-primary text-base-100 btn-sm" />
        </div>
    </div>
    <x-modal class="popUpMessage" wire:model="batchModal">
        <div class="flex flex-col relative h-full">
            <div class="bg-white">
                <div class="flex items-center gap-5 py-4 px-6" wire:loading.class="opacity-50">
                    <x-button
                        icon="o-arrow-small-left"
                        class="btn-sm btn-outline btn-primary"
                        label="Previous"
                        wire:click="previous"
                        spinner
                        :disabled="$selectedArticleIdx === 0"
                    />

                    <div class="w-full">
                        <x-select class="btn-sm pr-8" :options="$batch->articles->toArray()" wire:model.change="selectedArticleId" option_label="title" />
                    </div>

                    <x-button
                        icon-right="o-arrow-small-right"
                        label="Next"
                        wire:click="next"
                        class="btn-sm btn-outline btn-primary"
                        spinner
                        :disabled="$selectedArticleIdx == $batch->articles->count() - 1"
                    />
                    <x-button class="btn-sm px-[6px] bg-transparent border-0" icon="bi.x-lg"
                        @click="$wire.batchModal = false" />
                </div>
            </div>
            @if($selectedArticle)
            <x-article-view :selectedArticle="$selectedArticle" />
            @endif
        </div>
    </x-modal>
</div>
