<div>
    <x-header class="text-[40px]" title="History" subtitle="Your latest batches." />

    @php
    $headers = [
    ['key' => 'summary', 'label' => 'Summary'],
    ['key' => 'status', 'label' => 'Status'],
    ];
    @endphp

    <x-table :headers="$headers" :rows="$batches">
        @scope('actions', $batch)
        <x-button label="View" link="{{ route('history-view', ['id' => $batch->id]) }}" spinner class="btn-sm" />
        @endscope
    </x-table>
</div>
