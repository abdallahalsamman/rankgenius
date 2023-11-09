@extends("layouts.app")

@section('content')
<div class="flex">
    <div class="w-1/4 bg-gray-100 h-screen">
        <x-menu activate-by-route active-bg-color="bg-blue-50" class="border border-dashed">

            <x-menu-item title="Generate Articles" icon="o-plus" />
            <x-menu-item title="Navigate to Alert docs" icon="o-arrow-right" link="/docs/components/alert" />

            <x-menu-separator />

            <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                <x-menu-item title="Wifi" icon="o-wifi" />
                <x-menu-item title="Archives" icon="o-archive-box" />
            </x-menu-sub>

            <x-menu-separator title="Magic" icon="o-sparkles" />
            <x-menu-item title="Hello" badge="7" badge-classes="!badge-warning !text-red-500" />

            {{-- When route matches `link` property it activates menu --}}
            <x-menu-item title="Active state" link="/docs/components/menu" />

            <x-menu-separator title="Tricks" />
            <x-menu-item title="Hi" />
            <x-menu-item title="Some style" class="text-purple-500 font-bold" />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button label="Logout" icon="o-arrow-left-on-rectangle" type="submit" />
            </form>
        </x-menu>
    </div>
    <div class="w-3/4 bg-white h-screen">
        cccc
    </div>
</div>
@endsection