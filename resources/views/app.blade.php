<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>ViWallet</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('ViWallet.ico') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register("{{ asset('serviceworker.js') }}");
            }
        </script>
    </head>
    <body class="antialiased bg-gray-200">
        @inertia
    </body>
</html>
