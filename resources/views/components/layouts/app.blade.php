<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1440">

    <title>{{ $title ?? 'RankGenius' }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leader-line/1.0.3/leader-line.min.js" integrity="sha512-aFBNsI3+D6ObLLtyKwdZPZzDbcCC6+Bh+2UNV8HC0R95BpcBT+dmmZ5NMpJi/Ic8uO0W7FGcg33IfuHg+7Ryew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @yield('other-scripts')
</head>

<body>
    <livewire:toasts />

    @yield('content')

    @livewireScriptConfig
</body>

</html>
