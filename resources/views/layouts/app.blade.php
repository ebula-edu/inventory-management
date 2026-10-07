<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory System')</title>

    {{-- Iconography & Typography --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Application Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Desktop Header & Navigation --}}
    @include('partials.topbar')

    {{-- Mobile Navigation Drawer --}}
    @include('partials.sidebar')

    {{-- Primary Content Canvas --}}
    <main class="content">
        @include('partials.alerts')
        @yield('content')
    </main>

    {{-- Mobile Bottom Navigation --}}
    @include('partials.mobile-tabs')

    {{-- Modular Application Script --}}
    <script type="module" src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')

</body>
</html>
