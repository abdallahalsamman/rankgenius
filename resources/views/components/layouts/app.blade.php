<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1440">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <livewire:toasts />

    @yield('content')

    @livewireScriptConfig
</body>

</html>