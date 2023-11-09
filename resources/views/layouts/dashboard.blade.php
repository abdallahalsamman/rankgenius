@extends("layouts.app")

@section('content')
<div class="flex">
    <div class="min-w-[260px] bg-[#F7FAFC] h-screen mt-[30px]">
        <div class="text-2xl text-center p-4">Nazim</div>
        <x-menu activate-by-route active-bg-color="text-purple-500 font-semibold" class="border border-dashed">
            <div class="flex flex-col justify-center gap-3 text-[17px]">

                <div class="text-[12px] text-[#757575] pl-2 pt-6">ARTICLES</div>
                <x-menu-item title="Generate Articles" icon="o-plus"  link="{{ route('dashboard') }}"/>
                <x-menu-item title="History" icon="o-clock"  link="{{ route('history') }}"/>

                <div class="text-[12px] text-[#757575] pl-2 pt-6">CUSTOMIZATION</div>
                <x-menu-item title="Presets" icon="s-square-3-stack-3d" />
                <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                <x-menu-item title="Custom Images" icon="o-clock" />

                <x-menu-item title="Hello" badge="7" badge-classes="!badge-warning !text-red-500" />

                {{-- When route matches `link` property it activates menu --}}
                <x-menu-item title="Active state" />

                <x-menu-item title="Hi" />
                <x-menu-item title="Some style" class="text-purple-500 font-bold" />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button label="Logout" icon="o-arrow-left-on-rectangle" type="submit" />
                </form>
            </div>
        </x-menu>
    </div>
    <div class="w-3/4 bg-white h-screen">
        @yield('dashboard-content')
    </div>
</div>
@endsection
