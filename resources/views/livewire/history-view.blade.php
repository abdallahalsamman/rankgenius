<div>
    <x-header size="text-xl font-[700]" title="Batch" />
    <div class="grid grid-row-4">
        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">STATUS</div>
            <div class="w-1/2">
                @php
                $statusColor = [
                    \App\Enums\BatchStatusEnum::IN_PROGRESS->value => "bg-blue-100 text-info-content",
                    \App\Enums\BatchStatusEnum::DONE->value => "bg-green-100 text-success-content",
                    \App\Enums\BatchStatusEnum::CANCELLED->value => "bg-red-100 text-error-content"
                ];
                @endphp
                <div class="{{ $statusColor[$batch->status] }} rounded text-sm w-fit font-medium py-1 px-2">{{ Str::title($batch->status) }}</div>
            </div>
        </div>

        <div class="w-full flex items-center px-6 py-3  border-b-[1px] border-base-200 ">
            <div class="w-1/2 font-bold text-sm text-gray-600">{{ $batch->mode }}</div>
            <div class="w-1/2">
                <pre>{{ $batch->summary }}</pre>
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
            <div class="w-1/2">
                1 / {{ $batch->quantity }}
            </div>
        </div>

    </div>

    <div class="mt-5 flex justify-between">
        <div>
            <x-button label="asldfjk" />
        </div>
        <div>
            <x-button label="asldfjk" />
        </div>
    </div>
</div>