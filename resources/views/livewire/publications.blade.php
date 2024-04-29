<div>
    <x-header class="" size="text-xl font-[700]" subtitle="Everytime an article is published to one of your integrations, a Publication is created and displayed here. Data refreshed every 10 seconds." title="Publications" />

    {{-- <div class="grid grid-cols-[_80px_6fr_1fr_80px] gap-3 border-b-[1px]">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600 ml-3">DATE
        </div>
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600">
            ARTICLE
        </div>
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600">
            INTEGRATION
        </div>
        <div class="py-2 text-xs font-bold tracking-wider text-center text-gray-600">
            URL
        </div>
    </div>
    @forelse ($publications as $publication)
    <div class="grid grid-cols-[_80px_6fr_1fr_80px] gap-3 py-2 items-center hover:bg-base-200/50">
        <div class="text-left hover:bg-base-200/50 ml-3">{{ date('d/m', strtotime($publication->created_at)) }}
</div>
<div>{{ $publication->article->title }}</div>
<div>{{ $publication->integration->name }}</div>
<div class="text-center">
    <x-button class="btn-sm" label="View" :link="$publication->url" />
</div>
</div>
@empty
<div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
    All your publications will show up here.
</div>
@endforelse --}}
@php
$publications = auth()->user()->publications()->paginate(30);

$headers = [
['key' => 'created_at', 'label' => 'Date', 'class' => 'w-4'],
['key' => 'article.title', 'label' => 'Title'],
['key' => 'integration.name', 'label' => 'Integration', 'class' => 'w-3'],
];
@endphp

<x-table :headers="$headers" :rows="$publications" with-pagination />
</div>