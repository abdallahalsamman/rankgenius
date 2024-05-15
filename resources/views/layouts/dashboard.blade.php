@extends('components.layouts.app')

@section('content')
<div class="bg-white">
    {{-- logo --}}
    <div class="flex items-center justify-between px-6 py-5">
        <div class="flex items-end">
            <a href="{{ route('dashboard') }}">
                <div class="flex items-end gap-3">
                    <img class="w-6" src="{{ asset('resources/images/logo-b.png') }}" />
                    <!-- <p class="text-[1.25rem] font-bold">{{ env('APP_NAME') }}</p> -->
                    <div>
                        <span class="text-[#757575]">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </a>
            <x-badge class="btn-xs ml-3 bg-neutral text-white" value="3 Credits Left" />
        </div>
        <div class="flex items-center gap-5 text-sm">
            <a class="text-gray-600 hover:text-neutral-900" data-canny-link href="https://seoyousoon.canny.io">
                Give feedback
            </a>
            <x-button class="btn-sm border-2 bg-transparent text-gray-600 hover:text-neutral-900" label="Logout" link="{{ route('logout') }}" />
        </div>
        <!-- Download Canny SDK -->
        <script>
            ! function(w, d, i, s) {
                function l() {
                    if (!d.getElementById(i)) {
                        var f = d.getElementsByTagName(s)[0],
                            e = d.createElement(s);
                        e.type = "text/javascript", e.async = !0, e.src = "https://canny.io/sdk.js", f.parentNode.insertBefore(e, f)
                    }
                }
                if ("function" != typeof w.Canny) {
                    var c = function() {
                        c.q.push(arguments)
                    };
                    c.q = [], w.Canny = c, "complete" === d.readyState ? l() : w.attachEvent ? w.attachEvent("onload", l) : w.addEventListener("load", l, !1)
                }
            }(window, document, "canny-jssdk", "script");
        </script>

        <!-- Use the Canny SDK to identify the current user of your website -->
        <script>
            Canny('identify', {
                appID: '652f9fd822e07117d78ed917',
                user: {
                    email: "{{ auth()->user()->email }}",
                    name: "{{ auth()->user()->name }}",
                    id: "{{ auth()->user()->id }}",
                },
            });
        </script>
    </div>

    <div class="flex border-b-[1px] px-3 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}">
            <div class="{{ request()->routeIs('dashboard') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    Generate
                </div>
            </div>
        </a>

        <a href="{{ route('history') }}">
            <div class="{{ request()->routeIs('history') || request()->routeIs('history.view') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    History
                </div>
            </div>
        </a>

        <a href="{{ route('articles') }}">
            <div class="{{ request()->routeIs('articles') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    Articles
                </div>
            </div>
        </a>

        {{-- <a href="{{ route('presets') }}">
        <div class="{{ request()->routeIs('presets') || request()->routeIs('preset.create') || request()->routeIs('preset.edit') ? 'border-b-[1px] border-neutral-900' : '' }}">
            <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                Presets
            </div>
        </div>
        </a>

        <a href="{{ route('autoblogs') }}">
            <div class="{{ request()->routeIs('autoblogs') || request()->routeIs('autoblog.create') || request()->routeIs('autoblog.edit') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    Bots
                </div>
            </div>
        </a> --}}

        <a href="{{ route('integrations') }}">
            <div class="{{ request()->routeIs('integrations') || request()->routeIs('integration.create') || request()->routeIs('integration.edit') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    Integrations
                </div>
            </div>
        </a>


        <a href="{{ route('publications') }}">
            <div class="{{ request()->routeIs('publications') ? 'border-b-[1px] border-neutral-900' : '' }}">
                <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                    Publications
                </div>
            </div>
        </a>

        {{-- <a href="{{ route('pricing') }}">
        <div class="{{ request()->routeIs('pricing') ? 'border-b-[1px] border-neutral-900' : '' }}">
            <div class="mb-2 rounded-md px-3 py-1 hover:bg-gray-200 hover:text-neutral-900">
                Subscriptions
            </div>
        </div>
        </a> --}}
    </div>
    <div class="w-full max-w-[1024px] mx-auto my-10">
        @yield('dashboard-content')
    </div>
</div>
@endsection