<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · IntelliCareer</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('dashboard/images/favicon/favicon-32x32.png') }}">
    <script src="{{ asset('dashboard/js/vendors/color-modes.js') }}"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('dashboard/vendors/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/caria-brand.css') }}">
</head>
<body class="ca-auth-page">

<div class="ca-auth-shell">

    {{-- LEFT: Form side --}}
    <div class="ca-auth-form-side">
        <div class="ca-auth-form-top">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="IntelliCareer" width="32" height="32">
                <span class="ca-logo-text">IntelliCareer</span>
            </a>

            <div class="dropdown">
                <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <i class="ti theme-icon-active lh-1"><i class="ti theme-icon ti-sun"></i></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true"><i class="ti theme-icon ti ti-sun"></i><span class="ms-2">Light</span></button></li>
                    <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false"><i class="ti theme-icon ti-moon-stars"></i><span class="ms-2">Dark</span></button></li>
                    <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false"><i class="ti theme-icon ti-circle-half-2"></i><span class="ms-2">Auto</span></button></li>
                </ul>
            </div>
        </div>

        <div class="ca-auth-form-body">
            <div class="ca-auth-form-inner">
                <div class="mb-4">
                    @yield('header')
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    {{-- RIGHT: Brand hero side - animated product preview --}}
    <div class="ca-auth-hero-side">
        @include('components.auth-preview-stack')

        <div class="ca-auth-hero-bottom">
            <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="" width="28" height="28">
            <span class="ca-logo-text">IntelliCareer</span>
        </div>
    </div>
</div>

<script src="{{ asset('dashboard/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')

@include('components.toast-notifications')
</body>
</html>
