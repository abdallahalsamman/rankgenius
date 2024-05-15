<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1440">

    <title>@env('local') &#128296; @endenv @production &#128200; @endenv {{ $title ?? env('APP_NAME') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HGXWDWVG8G"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-HGXWDWVG8G');
    </script>
</head>

<body>
    <livewire:toasts />

    @yield('content')

    @livewireScriptConfig
</body>

</html>