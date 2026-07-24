<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title') - {{ setting('restaurant_name', 'مطعمنا') }}</title>
    <script>
        !(function() {
            try {
                var t = localStorage.getItem("dash26-theme"),
                    e = window.matchMedia("(prefers-color-scheme: dark)").matches;
                document.documentElement.setAttribute(
                    "data-theme",
                    t || (e ? "dark" : "light"),
                );
            } catch (t) {
                document.documentElement.setAttribute("data-theme", "light");
            }
        })();
    </script>
    <script defer="defer" src="{{ asset('assets/js/runtime.js') }}"></script>
    <script defer="defer" src="{{ asset('assets/js/vendor-fullcalendar.js') }}"></script>
    <script defer="defer" src="{{ asset('assets/js/vendor-chartjs.js') }}"></script>
    <script defer="defer" src="{{ asset('assets/js/vendors.js') }}"></script>
    <script defer="defer" src="{{ asset('assets/js/2026.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&family=Alexandria:wght@400;500;600;700&family=Almarai:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/globals.css') }}?v={{ time() }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}?v={{ time() }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/rtl.css') }}?v={{ time() }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/reservation-timeline.css') }}?v={{ time() }}" rel="stylesheet" />
    @vite(['resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        :root[data-theme="light"] {
            @if(setting('primary_color'))
                --primary: {{ setting('primary_color') }} !important;
                --primary-light: {{ setting('primary_color') }}d9 !important;
                --primary-dark: {{ setting('primary_color') }} !important;
                --primary-soft: {{ setting('primary_color') }}15 !important;
                --primary-ring: {{ setting('primary_color') }}33 !important;
            @endif
            @if(setting('bg_sidebar_light'))
                --bg-sidebar: {{ setting('bg_sidebar_light') }} !important;
            @endif
        }
        :root[data-theme="dark"] {
            @if(setting('primary_dark_color'))
                --primary: {{ setting('primary_dark_color') }} !important;
                --primary-light: {{ setting('primary_dark_color') }}d9 !important;
                --primary-dark: {{ setting('primary_dark_color') }} !important;
                --primary-soft: {{ setting('primary_dark_color') }}20 !important;
                --primary-ring: {{ setting('primary_dark_color') }}40 !important;
            @endif
            @if(setting('bg_sidebar_dark'))
                --bg-sidebar: {{ setting('bg_sidebar_dark') }} !important;
            @endif
        }
        body, button, input, select, textarea, .d-sidebar, .card, .btn {
            @if(setting('font_family'))
                font-family: '{{ setting('font_family') }}', 'Cairo', sans-serif !important;
            @endif
        }
    </style>
</head>


<body data-active="dashboard" data-crumbs="Workspace | Dashboard">
    <div class="@yield('shell-class')">


        @auth
            @include('parts.aside')
        @endauth

        <div class="@yield('main-class')">
            @auth
                @include('parts.header')
            @endauth


            @yield('content')

            @include('parts.footer')
        </div>
    </div>
    @livewireScripts
</body>

</html>
