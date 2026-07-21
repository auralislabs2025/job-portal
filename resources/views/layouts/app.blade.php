<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ABN Recruitment Portal'))</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar" id="sidebar-container">
            <x-sidebar />
        </aside>
        <main class="main-content">
            <header class="topbar" id="topbar-container">
                <x-topbar />
            </header>
            <div class="page-content">
                @if (isset($header))
                    <div class="page-header">
                        {{ $header }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <x-flash-messages />
    <x-confirm-modal />
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
