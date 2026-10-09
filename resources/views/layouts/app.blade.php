<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory System')</title>

    {{-- Iconography & Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Application Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Layout shell: sidebar + main column --}}
    <div class="app-shell">

        {{-- Permanent left-sidebar (desktop) / drawer (mobile) --}}
        @include('partials.sidebar')

        {{-- Right-side main column --}}
        <div class="main-column">

            {{-- Slim header bar --}}
            @include('partials.topbar')

            {{-- Primary content canvas --}}
            <main class="content" id="mainContent">
                @include('partials.alerts')
                @yield('content')
            </main>

        </div>{{-- /.main-column --}}

    </div>{{-- /.app-shell --}}

    {{-- Mobile Bottom Navigation --}}
    @include('partials.mobile-tabs')

    {{-- Modular Application Script --}}
    <script type="module" src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')

</body>
</html>
