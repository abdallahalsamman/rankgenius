<div>
    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="An Autoblog allows {{ env('APP_NAME') }} to generate articles in a certain schedule and send them to one of your integrations."
        title="AutoBlogs" />

    <div class="grid grid-cols-[_6fr_1fr_1fr_1fr] gap-3 border-b-[1px]">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600 ml-3">NAME
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
    @foreach ($autoBlogs as $autoBlog)
        <div class="grid grid-cols-[_6fr_1fr_1fr_1fr] gap-3 py-2 text-center items-center hover:bg-base-200/50">
            <div class="text-left ml-3">{{ Str::limit($autoBlog->name, 80, ' ...') }}</div>
            <div class="w-full flex justify-center">
                <div class="{{$autoBlog->status ? 'bg-green-200' : 'bg-gray-300'}} text-info-content whitespace-nowrap rounded text-sm w-fit font-medium py-1 px-2">{{$autoBlog->status ? 'Active' : 'Pause'}}</div>
            </div>
            <div>add next batch here</div>
            <div class="flex justify-around">
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-red-200"
                    icon="bi.trash" spinner
                    wire:click="deleteConfirm('{{ $autoBlog->id }}')" />
                <x-button
                    class="btn-sm border-none bg-transparent hover:bg-green-200"
                    icon="bi.pencil-square"
                    link="{{ route('autoblog.edit', ['id' => $autoBlog->id]) }}"
                    wire:loading.attr="disabled" />
            </div>
        </div>
    @endforeach


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
