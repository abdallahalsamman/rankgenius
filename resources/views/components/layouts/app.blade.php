<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    @yield('content')

    @livewireScripts
    <script>
        // hook to catch after every livewire request
        Livewire.hook('message.processed', (message, component) => {

            let errors = message.response.serverMemo.errors;

            // Only scroll if we have an error bag with validation messages
            if (Object.keys(errors).length > 0) {

                let errorDiv = document.getElementsByClassName('invalid-feedback')[0];

                // if we have an error element and it's not visible in viewport
                if (errorDiv && !isScrolledIntoView(errorDiv)) {

                    // if user is typing on an element they're trying to fix then DO NOT SCROLL to the next error message
                    // otherwise the user might be typing and will scroll away from them as they're trying to fix the error
                    if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !==
                        'TEXTAREA') {
                        errorDiv.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center',
                            inline: 'nearest'
                        });
                    }

                }
            }
        })

        // function to determine if an element is currently in viewport
        function isScrolledIntoView(el) {
            let rect = el.getBoundingClientRect();
            let elemTop = rect.top;
            let elemBottom = rect.bottom;

            // return true if element visible in viewport
            return elemTop < window.innerHeight && elemBottom >= 0
        }
    </script>
</body>

</html>
