<div>
    <x-header size="text-xl font-[700]" title="History" subtitle="Your latest batches." />

    @php
    $headers = [
        ['key' => 'summary', 'label' => 'SUMMARY', 'class' => 'text-gray-600 tracking-wider'],
        ['key' => 'status', 'label' => 'STATUS', 'class' => 'text-gray-600 tracking-wider'],
    ];

    @endphp

    <x-table class="" :headers="$headers" :rows="$batches">
        @scope('cell_summary', $batch)
            {{ Str::limit($batch->summary, 80, ' ...') }}
        @endscope

        @scope('cell_status', $batch)
        @inject('Batch', \App\Models\Batch::class)
        @php
        $STATUS = $Batch::$STATUS;
        $statusColor = [
            $STATUS['IN_PROGRESS'] => "bg-blue-100 text-info-content",
            $STATUS['DONE'] => "bg-green-100 text-success-content",
            $STATUS['CANCELLED'] => "bg-red-100 text-error-content"
        ];
        @endphp
        <div class="{{ $statusColor[$batch->status] }} whitespace-nowrap rounded text-sm w-fit font-medium py-1 px-2">{{ $batch->status }}</div>
        @endscope

        @scope('actions', $batch)
        <x-button label="View" link="{{ route('history-view', ['id' => $batch->id]) }}" spinner class="btn-sm" />
        @endscope
    </x-table>
</div>
