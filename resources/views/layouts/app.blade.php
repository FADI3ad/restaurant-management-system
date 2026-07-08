<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title')</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet" />
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

</body>

</html>
