@extends('components.layouts.app')

@section('content')
<div class="container mx-auto h-screen px-4">
    <div class="flex h-full content-center items-center justify-center">
        <div class="w-4/12 px-4 md:w-full">
            <div class="relative mb-6 flex w-full min-w-0 flex-col break-words rounded-lg border-0 bg-white shadow-lg">
                <div class="mb-0 rounded-t px-6 py-6">
                    <div class="mb-3 text-center">
                        <h6 class="text-sm font-bold text-gray-600">
                            {{ env('APP_NAME') }}
                        </h6>
                    </div>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="relative mb-3 w-full">
                            <label class="mb-2 block text-xs font-bold uppercase text-gray-700" for="grid-password">
                                {{ __('Email Address') }}
                            </label>
                            <input autocomplete="email" autofocus class="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow transition-all duration-150 ease-linear focus:outline-none focus:ring" id="email" name="email" required type="email" value="{{ old('email') }}">

                            @if ($errors->any())
                            <div class="alert-danger alert mt-2" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li><strong>{{ $error }}</strong>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if (session('success'))
                            <div class="alert alert-success mt-2 text-white" role="alert">
                                <strong>{{ session('success') }}
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 text-center">
                            <button class="mb-1 mr-1 w-full rounded bg-blue-500 px-6 py-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-blue-600" type="submit">
                                {{ __('Send Login Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection