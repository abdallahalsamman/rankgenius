<div>
    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="An Autoblog allows ContentAIO to generate articles in a certain schedule and send them to one of your integrations."
        title="AutoBlogs" />

    <div class="grid grid-cols-[_6fr_1fr_1fr_1fr] gap-3 border-b-[1px]">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600">NAME
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            STATUS
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            NEXT BATCH
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            ACTIONS
        </div>
    </div>
    <div class="mt-2 grid grid-cols-[_6fr_1fr_1fr_1fr] gap-3 text-center">
        @foreach ($autoBlogs as $autoBlog)
            <div class="text-left">{{ Str::limit($autoBlog->name, 80, ' ...') }}
            </div>
            <div>ddsddsds</div>
            <div>dsdsdsds</div>
            <div class="flex justify-around">
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-red-100"
                    icon="bi.trash"
                    wire:click="deleteConfirm('{{ $autoBlog->id }}')"
                    spinner />
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-green-100"
                    icon="bi.pencil-square"
                    link="{{ route('autoblog.edit', ['id' => $autoBlog->id]) }}"
                    wire:loading.attr="disabled" />
            </div>
        @endforeach
    </div>


    <x-modal title="Are you sure?" wire:model="deleteModal">
        Click "cancel" or press ESC to exit.

        <x-slot:actions>
            {{-- Note `onclick` is HTML --}}
            <x-button @click="$wire.deleteModal = false" label="Cancel" />
            <x-button class="btn-error" label="Delete"
                wire:click="delete('{{ $autoBlogIdToDelete }}')" />
        </x-slot:actions>
    </x-modal>

    @if ($autoBlogs->isEmpty())
        <div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
            Click below to create your first AutoBlog.
        </div>
    @endif

    <x-button class="btn-primary mt-8 w-full text-base-100" icon="o-plus"
        label="New AutoBlog" link="{{ route('autoblog.create') }}" />

</div>
