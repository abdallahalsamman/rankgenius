@extends("layouts.app")

@section('content')
<div class="flex h-screen max-h-screen">
    <div class="min-w-[260px] bg-base-300 h-full">
        <x-menu activate-by-route active-bg-color="text-purple-500 font-semibold" class="border border-dashed h-full">
            <div class="h-full text-[17px] grid grid-rows-[50px_minmax(300px,_1fr)_160px]">
                <div class="text-2xl text-center">Nazim</div>
                <div class="flex flex-col gap-[0.4rem] overflow-scroll pb-8">
                    <div class="text-[12px] text-[#757575] pl-2 pt-6">ARTICLES</div>
                    <x-menu-item title="Generate Articles" icon="o-plus" link="{{ route('dashboard') }}" />
                    <x-menu-item title="History" icon="o-clock" link="{{ route('history') }}" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">CUSTOMIZATION</span>
                    <x-menu-item title="Presets" icon="s-square-3-stack-3d" />
                    <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                    <x-menu-item title="Custom Images" icon="m-photo" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">AUTOMATION</span>
                    <x-menu-item title="Presets" icon="s-square-3-stack-3d" />
                    <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                    <x-menu-item title="Custom Images" icon="m-photo" />
                    <x-menu-item title="Presets" icon="s-square-3-stack-3d" />
                    <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                    <x-menu-item title="Custom Images" icon="m-photo" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">ACCOUNT</span>
                    <x-menu-item title="Invite users" icon="s-user-group" />
                    <x-menu-item title="Subscription" icon="m-credit-card" />
                </div>

                <div class="flex flex-col gap-[0.4rem]">
                    <x-menu-item title="3 Credits Left" icon="m-photo" class="bg-neutral text-base-300" />
                    <x-menu-item title="Request a Feature" icon="o-light-bulb" />
                    <x-menu-item title="Logout" icon="o-arrow-left-on-rectangle" link="{{ route('logout') }}" />
                </div>

            </div>
        </x-menu>
    </div>
    <div class="w-3/4 bg-white">
        @yield('dashboard-content')
    </div>
</div>
@endsection
