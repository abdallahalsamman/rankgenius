<div>
    <x-header size="text-xl font-[700] mb-10" class="" title="Presets" subtitle="Presets tell Journalist AI how articles should be generated. You can modify a Preset and reuse it multiple times." />

    @php
    $headers = [
    ['key' => 'name', 'label' => 'NAME', 'class' => 'text-gray-600 tracking-wider'],
    ['key' => 'actions', 'label' => 'ACTIONS', 'class' => 'text-gray-600 tracking-wider']
    ];
    $presets = [];
    @endphp

    <x-table class="" :headers="$headers" :rows="$presets">
        @scope('cell_summary', $batch)
        {{ Str::limit($batch->name, 80, ' ...') }}
        @endscope

        <div class="whitespace-nowrap rounded text-sm w-fit font-medium py-1 px-2">dddd</div>

        @scope('actions', $batch)
        <x-button label="Edit" link="{{ route('preset-edit', ['id' => $batch->id]) }}" spinner class="btn-sm" />
        <x-button label="Duplicate" spinner class="btn-sm" />
        @endscope
    </x-table>


    @if(empty($presets))
    <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first Preset.</div>
    @endif

    <x-button label="New Preset" link="{{ route('preset-view')}}" icon="o-plus" spinner class="btn-primary text-base-100 mt-8 w-full" />

</div>
