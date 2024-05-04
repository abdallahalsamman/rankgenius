<div>
    <div class="flex justify-between items-center mb-10">
        <x-header class="!mb-0" size="text-xl font-[700]"
            subtitle="Presets tell {{ env('APP_NAME') }} how articles should be generated. You can modify a Preset and reuse it multiple times."
            title="Presets" />

        <x-button
            class="bg-neutral-900 font-semibold btn-sm px-5 text-white hover:bg-gray-700"
            icon-right="o-plus" label="New Preset"
            link="{{ route('preset.create') }}" />
    </div>

    <div class="grid grid-cols-[_3fr_1fr] gap-3 border-b-[1px]">
        <div class="ml-3 py-2 text-xs font-bold tracking-wider text-gray-600">
            NAME
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            ACTIONS</div>
    </div>
    @foreach ($presets as $preset)
        <div
            class="grid grid-cols-[_3fr_1fr] items-center gap-3 py-2 hover:bg-base-200/50">
            <div class="ml-3">{{ Str::limit($preset->name, 80, ' ...') }}
            </div>

            <div class="flex justify-around px-10">
                <div>
                    <x-button
                        class="btn-sm border-none bg-transparent hover:bg-red-200"
                        icon="bi.trash" spinner
                        wire:click="deleteConfirm('{{ $preset->id }}')" />
                </div>
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-green-200"
                    icon="bi.pencil-square"
                    link="{{ route('preset.edit', ['id' => $preset->id]) }}" />
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-blue-200"
                    icon="s-document-duplicate" spinner
                    wire:click="clone('{{ $preset->id }}')" />
            </div>
        </div>
    @endforeach

    <x-modal title="Are you sure?" wire:model="deleteModal">
        Click "cancel" or press ESC to exit.

        <x-slot:actions>
            {{-- Note `onclick` is HTML --}}
            <x-button @click="$wire.deleteModal = false" label="Cancel" />
            <x-button class="btn-error" label="Delete"
                wire:click="delete('{{ $presetIdToDelete }}')" />
        </x-slot:actions>
    </x-modal>

    @if ($presets->isEmpty())
        <div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
            Click below to create your first Preset.
        </div>
    @endif

</div>
