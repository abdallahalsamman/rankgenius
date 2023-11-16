@extends("components.layouts.app")

@section('content')
<div class="flex w-full md:flex-col h-screen max-h-screen md:h-full md:max-h-full">
    <div class="min-w-[260px] md:w-full bg-[#f7fafc] h-full">
        <x-menu activate-by-route active-bg-color="text-purple-500 font-semibold" class="border p-0 border-dashed h-full">
            <div class="h-full text-[17px] grid grid-rows-[60px_minmax(300px,_1fr)_160px] md:block">
                <div class="h-[60px] md:mt-6 md:pb-5 border-b-[1px] w-full px-2 border-[#bebebe]">
                    <a href="{{ route('dashboard') }}">
                        <div class="w-full h-full flex items-center pl-[11px]">
                            <x-icon name="iconsax.bul-ranking" class="w-[30px] h-[30px]" />
                            <span class="text-[25px] pl-2 font-bold">ContentAIO</span>
                        </div>
                    </a>
                </div>
                <div class="flex flex-col border-b-[1px] border-[#bebebe] gap-[0.4rem] overflow-y-scroll pb-8 p-2 scrollable-div">
                    <div class="text-[12px] text-[#757575] pl-2 pt-6">ARTICLES</div>
                    <x-menu-item title="Generate Articles" icon="o-plus" link="{{ route('dashboard') }}" />
                    <x-menu-item title="History" icon="o-clock" link="{{ route('history') }}" />
                    <x-menu-item title="All Articles" icon="bi.grid-3x3-gap-fill" link="{{ route('articles') }}" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">CUSTOMIZATION</span>
                    <x-menu-item title="Presets" icon="s-square-3-stack-3d" link="{{ route('presets') }}" />
                    <!-- <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                    <x-menu-item title="Custom Images" icon="m-photo" /> -->

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">AUTOMATION</span>
                    <x-menu-item title="AutoBlogs" icon="fas.robot" link="{{ route('autoblogs') }}" />
                    <x-menu-item title="Integrations" icon="fab.wordpress" />
                    <!-- <x-menu-item title="Calendar" icon="s-calendar-days" /> -->
                    <!-- <x-menu-item title="Indexers" icon="phosphor.list-checks-bold" /> -->
                    <x-menu-item title="Publications" icon="phosphor.telegram-logo-thin" />
                    <!-- <x-menu-item title="Keyword Monitors" icon="m-magnifying-glass" /> -->

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">ACCOUNT</span>
                    <!-- <x-menu-item title="Invite users" icon="s-user-group" /> -->
                    <x-menu-item title="Subscription" icon="m-credit-card" />
                </div>

                <div class="flex flex-col gap-[0.4rem] pt-5 p-2">
                    <x-menu-item title="3 Credits Left" icon="phosphor.coins-light" class="bg-neutral text-base-300" />
                    <x-menu-item title="Request a Feature" icon="o-light-bulb" />
                    <x-menu-item title="Logout" icon="o-arrow-left-on-rectangle" link="{{ route('logout') }}" />
                </div>

            </div>
        </x-menu>
    </div>
    <div class="w-full max-w-full bg-white p-10 overflow-y-scroll">
        {{ $slot }}
    </div>
</div>
@endsection
