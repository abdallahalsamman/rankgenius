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
                    1 / {{ $batch->quantity }}
                </div>
                <div>
                    <x-button icon="s-eye" label="View" class="btn-primary text-base-100 btn-sm" />
                    <x-button icon="phosphor.download-simple-bold" label=".zip" class="btn-primary btn-outline btn-sm ml-1" />
                </div>
            </div>
        </div>

    </div>

    <div class="mt-10 flex justify-between">
        <div class="flex gap-2">
            <x-select class="select-sm"/>
            <x-button label="Publish all to Integration" class="btn-primary text-base-100 btn-sm" />
        </div>
        <div>
            <x-button icon="o-plus-small" label="Generate More" class="btn-primary text-base-100 btn-sm" />
        </div>
    </div>
</div>
