<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>لوحة التحكم · معاينة تصميم 2026</title>
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
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet" />
</head>


<body data-active="dashboard" data-crumbs="Workspace | Dashboard">
    <div class="shell">
      
        @include('parts.aside')
        <div class="main">
            @include('parts.header')

            @yield('content')

            @include('parts.footer')
        </div>
    </div>

</body>

</html>
