<div>
    <x-header size="text-xl font-[700] mb-10" class="" title="Presets"
        subtitle="Presets tell Journalist AI how articles should be generated. You can modify a Preset and reuse it multiple times." />

    <div class="grid grid-cols-[_3fr_1fr] gap-3">
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs">NAME</div>
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs text-center">ACTIONS</div>

        @foreach($presets as $preset)
        <div>{{ Str::limit($preset->name, 80, ' ...') }}</div>

        <div class="px-10 flex justify-around">
            <x-button icon="bi.trash" onclick="deleteModel.showModal()" tooltip="Delete" spinner class="btn-sm bg-transparent hover:bg-red-100 border-none" />
                <x-modal id="deleteModel" title="Are you sure?">
                    Click "cancel" or press ESC to exit.

                    <x-slot:actions>
                        {{-- Note `onclick` is HTML --}}
                        <x-button label="Cancel" onclick="deleteModel.close()" />
                        <x-button label="Delete" class="btn-error" wire:click="delete('{{$preset->id}}')" />
                    </x-slot:actions>
                </x-modal>
                <x-button icon="bi.pencil-square" tooltip="Edit" link="{{ route('preset-edit', ['id' => $preset->id]) }}"
                class="btn-sm bg-transparent hover:bg-green-100 border-none" />
            <x-button icon="s-document-duplicate" tooltip="Duplicate" spinner
                class="btn-sm bg-transparent hover:bg-blue-100 border-none" />
        </div>
        @endforeach
    </div>

    @if ($presets->isEmpty())
        <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first Preset.
        </div>
    @endif

    <x-button label="New Preset" link="{{ route('preset-view') }}" icon="o-plus"
        class="btn-primary text-base-100 mt-8 w-full" />

</div>
