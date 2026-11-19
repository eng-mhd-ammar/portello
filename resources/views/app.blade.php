<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
            (function () {
                var key = 'portello-theme';
                var stored = localStorage.getItem(key);
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = stored === 'dark' || stored === 'light' ? stored : prefersDark ? 'dark' : 'light';
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
                document.documentElement.dataset.theme = theme;
            })();
        </script>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>