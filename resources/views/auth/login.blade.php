@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 h-full">
    <div class="flex content-center items-center justify-center h-full">
        <div class="md:w-full w-4/12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
                <div class="rounded-t mb-0 px-6 py-6">
                    <div class="text-center mb-3">
                        <h6 class="text-gray-600 text-sm font-bold">
                            {{ __('Login') }}
                        </h6>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-gray-700 text-xs font-bold mb-2" for="grid-password">
                                {{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @if ($errors->any())
                            <div class="alert alert-danger mt-2" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li><strong>{{ $error }}</strong></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if (session('success'))
                            <div class="alert alert-success text-white mt-2" role="alert">
                                <strong>{{ session('success') }}
                            </div>
                            @endif
                        </div>

                        <div class="text-center mt-6">
                            <button class="bg-blue-500 text-white active:bg-blue-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150" type="submit">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
