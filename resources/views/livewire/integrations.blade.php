<div>
    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="Integrations allow you to publish your articles straight to your website."
        title="Integrations" />

    <div class="grid grid-cols-[_5fr_1fr] gap-3 border-b-[1px]">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600 ml-3">NAME
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            ACTIONS</div>
    </div>
    @foreach ($integrations as $integration)
        <div class="grid grid-cols-[_5fr_1fr] gap-3 pt-2 hover:bg-base-200/50">
            <div class="flex items-center">
                <x-icon name="bi.wordpress" class="mx-3" />
                <div>{{ Str::limit($integration->name, 80, ' ...') }}</div>
            </div>

            <div class="flex justify-around items-center px-10">
                <div>
                    <x-button
                        class="btn-sm border-none bg-transparent hover:bg-red-100"
                        icon="bi.trash" spinner
                        wire:click="deleteConfirm('{{ $integration->id }}')" />
                </div>
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-green-100"
                    icon="bi.pencil-square"
                    link="{{ route('integration.edit', ['id' => $integration->id]) }}" />
            </div>
        </div>
    @endforeach

    <x-modal title="Are you sure?" wire:model="deleteModal">
        Click "cancel" or press ESC to exit.

        <x-slot:actions>
            {{-- Note `onclick` is HTML --}}
            <x-button @click="$wire.deleteModal = false" label="Cancel" />
            <x-button class="btn-error" label="Delete"
                wire:click="delete('{{ $integrationIdToDelete }}')" />
        </x-slot:actions>
    </x-modal>

    @if ($integrations->isEmpty())
        <div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
            Click below to create your first integration.
        </div>
    @endif

    <x-button class="btn-primary mt-8 w-full text-base-100" icon="o-plus"
        label="New Integration" link="{{ route('integration.create') }}" />

</div>
