<div>
    <x-header size="text-xl font-[700] mb-10" class="" title="Presets"
        subtitle="Presets tell Journalist AI how articles should be generated. You can modify a Preset and reuse it multiple times." />

    @php
        $headers = [['key' => 'name', 'label' => 'NAME', 'class' => 'text-gray-600 tracking-wider'],
        ['key' => 'actions', 'label' => 'ACTIONS', 'class' => 'text-gray-600 tracking-wider']];
    @endphp

    <x-table :headers="$headers" :rows="$presets">
        @scope('cell_name', $batch)
            {{ Str::limit($batch->name, 80, ' ...') }}
        @endscope

        @scope('cell_actions', $batch)
            <div class="flex justify-end">
                <x-button icon="bi.x" label="Delete" spinner class="btn-sm bg-transparent hover:bg-red-100 border-none" />
                <x-button icon="bi.pencil-square" label="Edit" link="{{ route('preset-edit', ['id' => $batch->id]) }}"
                    class="btn-sm bg-transparent hover:bg-green-100 border-none" />
                <x-button icon="s-document-duplicate" label="Duplicate" spinner
                    class="btn-sm bg-transparent hover:bg-blue-100 border-none" />
            </div>
        @endscope
    </x-table>


    @if (empty($presets))
        <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first Preset.
        </div>
    @endif

    <x-button label="New Preset" link="{{ route('preset-view') }}" icon="o-plus"
        class="btn-primary text-base-100 mt-8 w-full" />

</div>
