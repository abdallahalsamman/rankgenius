<div>
    <x-header class="" size="text-xl font-[700]"
        subtitle="Everytime an article is published to one of your integrations, a Publication is created and displayed here. Data refreshed every 10 seconds."
        title="Publications" />

    <div class="grid grid-cols-[_80px_6fr_1fr_80px] gap-3 border-b-[1px]">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600 ml-3">DATE
        </div>
        <div
            class="py-2 text-xs font-bold tracking-wider text-gray-600">
            ARTICLE
        </div>
        <div
            class="py-2 text-xs font-bold tracking-wider text-gray-600">
            INTEGRATION
        </div>
        <div
            class="py-2 text-xs font-bold tracking-wider text-center text-gray-600">
            URL
        </div>
    </div>
    @foreach ($publications as $publication)
        <div class="grid grid-cols-[_80px_6fr_1fr_80px] gap-3 py-2 items-center hover:bg-base-200/50">
            <div class="text-left hover:bg-base-200/50 ml-3">{{ date('d/m', strtotime($publication->created_at)) }}</div>
            <div>{{ $publication->article()->value('title') }}</div>
            <div>{{ $publication->integration()->value('name') }}</div>
            <div class="text-center"><x-button class="btn-sm" label="View" :link="$publication->url"/></div>
        </div>
    @endforeach

    @if ($publications->isEmpty())
        <div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
            All your publications will show up here.
        </div>
    @endif

</div>
