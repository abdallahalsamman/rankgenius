@extends("layouts.app")

@section('content')
<div class="flex h-screen max-h-screen">
    <div class="min-w-[260px] bg-base-300 h-full">
        <x-menu activate-by-route active-bg-color="text-purple-500 font-semibold" class="border p-0 border-dashed h-full">
            <div class="h-full text-[17px] grid grid-rows-[50px_minmax(300px,_1fr)_160px]">
                <div class="text-2xl text-center border-b-[1px] border-[#bebebe]">Nazim</div>
                <div class="flex flex-col gap-[0.4rem] overflow-scroll pb-8 p-2 scrollable-div" id="menu-with-links">
                    <div class="text-[12px] text-[#757575] pl-2 pt-6">ARTICLES</div>
                    <x-menu-item title="Generate Articles" icon="o-plus" link="{{ route('dashboard') }}" />
                    <x-menu-item title="History" icon="o-clock" link="{{ route('history') }}" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">CUSTOMIZATION</span>
                    <x-menu-item title="Presets" icon="s-square-3-stack-3d" />
                    <x-menu-item title="Brands" icon="m-globe-asia-australia" />
                    <x-menu-item title="Custom Images" icon="m-photo" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">AUTOMATION</span>
                    <x-menu-item title="AutoBlogs" icon="fas.robot" />
                    <x-menu-item title="Integrations" icon="fab.wordpress" />
                    <x-menu-item title="Calendar" icon="s-calendar-days" />
                    <x-menu-item title="Indexers" icon="phosphor.list-checks-bold" />
                    <x-menu-item title="Publications" icon="phosphor.telegram-logo-thin" />
                    <x-menu-item title="Keyword Monitors" icon="m-magnifying-glass" />

                    <span class="text-[12px] text-[#757575] pl-2 pt-6">ACCOUNT</span>
                    <x-menu-item title="Invite users" icon="s-user-group" />
                    <x-menu-item title="Subscription" icon="m-credit-card" />
                </div>

                <div class="flex flex-col gap-[0.4rem] pt-5 p-2">
                    <x-menu-item title="3 Credits Left" icon="phosphor.coins-light" class="bg-neutral text-base-300" />
                    <x-menu-item title="Request a Feature" icon="o-light-bulb" />
                    <x-menu-item title="Logout" icon="o-arrow-left-on-rectangle" link="{{ route('logout') }}" />
                </div>

            </div>
        </x-menu>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const div = document.getElementById('menu-with-links');

                const checkScroll = () => {
                    if (div.scrollTop === 0) {
                        div.classList.remove('shadow-top');
                        div.classList.add('shadow-bottom');
                    } else if (div.scrollHeight - div.scrollTop === div.clientHeight) {
                        div.classList.remove('shadow-bottom');
                        div.classList.add('shadow-top');
                    } else {
                        div.classList.remove('shadow-top');
                        div.classList.remove('shadow-bottom');
                        div.classList.add('scrollable-div');
                    }
                };

                checkScroll();
                div.addEventListener('scroll', checkScroll);
            });
        </script>
    </div>
    <div class="w-3/4 bg-white">
        @yield('dashboard-content')
    </div>
</div>
@endsection
