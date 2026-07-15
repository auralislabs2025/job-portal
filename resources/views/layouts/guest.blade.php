<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ABN Recruitment Portal'))</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="8" fill="#0F1B2D"/>
                        <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#C8A94E" font-size="16" font-weight="700" font-family="sans-serif">ABN</text>
                    </svg>
                </div>
                <h1>ABN Corporation</h1>
                <p>Enterprise Recruitment Management System</p>
            </div>
            {{ $slot }}
            <div class="login-footer">
                <p>&copy; {{ date('Y') }} ABN Corporation. All rights reserved.</p>
                <p class="login-help">Contact IT Support: support@abncorporation.com</p>
            </div>
        </div>
    </div>
</body>
</html>
