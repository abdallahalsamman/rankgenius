<div>
    <x-header size="text-xl font-[700] mb-5" title="History" subtitle="Your latest batches." />

    @php
    $headers = [
    ['key' => 'details', 'label' => 'SUMMARY', 'class' => 'text-gray-600 tracking-wider'],
    ['key' => 'status', 'label' => 'STATUS', 'class' => 'text-gray-600 tracking-wider'],
    ];

    @endphp

    <x-table :headers="$headers" :rows="$batches">
        @scope('cell_details', $batch)
        <strong>{{ Str::title($batch->mode) }}</strong><br>
        {{ Str::limit($batch->details, 80, ' ...') }}
        @endscope

        @scope('cell_status', $batch)
        @php
        $statusColor = [
            \App\Enums\BatchStatusEnum::IN_PROGRESS->value => "bg-blue-100 text-info-content",
            \App\Enums\BatchStatusEnum::DONE->value => "bg-green-100 text-success-content",
            \App\Enums\BatchStatusEnum::CANCELLED->value => "bg-red-100 text-error-content"
        ];
        @endphp
        <div class="{{ $statusColor[$batch->status] }} whitespace-nowrap rounded text-sm w-fit font-medium py-1 px-2">{{ Str::title($batch->status) }}</div>
        @endscope

        @scope('actions', $batch)
        <x-button label="View" link="{{ route('history.view', ['id' => $batch->id]) }}" spinner class="btn-sm" />
        @endscope
    </x-table>

    @if($batches->isEmpty())
    <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">All your batches will show up here.</div>
    @endif
</div>
