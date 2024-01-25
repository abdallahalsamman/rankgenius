@extends('components.layouts.app')

@section('content')
<div>

    {{-- nav --}}
    <section class="fixed left-0 right-0 top-0 z-50 bg-white">
        <nav class="border-b-[1px]">
            <div class="my-4 flex items-center px-6">
                <a href="/">
                    <div class="flex items-center">
                        <div class="mr-1">
                            <img class="w-5"
                                src="{{ asset('resources/images/logo-b.png') }}" />
                        </div>
                        <p class="text-[1.25rem] font-bold">{{ env('APP_NAME') }}
                        </p>
                    </div>
                </a>
                <div class="ml-8 flex h-fit gap-[2rem]">
                    <a class="text-gray-600 hover:text-neutral-900"
                        href="/#features">Features</a>
                    <a class="text-gray-600 hover:text-neutral-900"
                        href="/#autoblog">AutoBlog</a>
                    <a class="text-gray-600 hover:text-neutral-900"
                        href="/#pricing">Pricing</a>
                    <a class="text-gray-600 hover:text-neutral-900"
                        href="/#faq">FAQ</a>
                    <a class="text-gray-600 hover:text-neutral-900"
                        href="/learn">Learn</a>
                </div>
                <div class="ml-auto flex justify-between gap-2">
                    @if (Auth::check())
                        <x-button
                            class="btn-sm border-2 bg-white text-neutral-900"
                            external label="Dashboard"
                            link="{{ route('dashboard') }}" />
                    @else
                        <x-button
                            class="btn-sm bg-gradient-to-r from-blue-500 to-teal-500 bg-300% animate-gradient font-semibold text-white"
                            external label="Get 5 Free Articles"
                            link="{{ route('dashboard') }}" />
                        <x-button
                            class="btn-sm border-2 bg-white text-neutral-900"
                            external label="Log In"
                            link="{{ route('login') }}" />
                    @endif
                </div>
            </div>
        </nav>
    </section>

    @yield('home-content')

    {{-- footer --}}
    <section>
        <footer>
            <div class="flex items-center gap-40 bg-neutral-900 px-12 py-12">
                <div class="flex items-center">
                    <div class="mr-3 w-6">
                        <img
                            src="{{ asset('resources/images/logo-w.png') }}" />
                    </div>
                    <div>
                        <h1 class="text-3xl text-white">{{ env('APP_NAME') }}
                        </h1>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    {{-- <div class="flex flex-row gap-4">
                        <x-icon class="h-6 w-6 text-gray-500"
                            name="bi.facebook" />
                        <x-icon class="h-6 w-6 text-gray-500"
                            name="bi.twitter" />
                        <x-icon class="h-6 w-6 text-gray-500"
                            name="bi.linkedin" />
                        <x-icon class="h-6 w-6 text-gray-500"
                            name="bi.instagram" />
                    </div> --}}
                    <div class="flex flex-col gap-4">
                        <a class="text-white hover:text-white"
                            href="{{ route('terms') }}">Terms</a>
                        <a class="text-white hover:text-white"
                            href="{{ route('privacy') }}">Privacy</a>
                    </div>
                </div>
        </footer>
    </section>
</div>
@endsection
