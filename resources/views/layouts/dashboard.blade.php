<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="expanded">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'IntelliCareer') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('dashboard/images/favicon/favicon-32x32.png') }}">

    <!-- Color modes (must be in head before page renders) -->
    <script src="{{ asset('dashboard/js/vendors/color-modes.js') }}"></script>
    <script>
        if (localStorage.getItem('sidebarExpanded') === 'false') {
            document.documentElement.classList.add('collapsed');
            document.documentElement.classList.remove('expanded');
        } else {
            document.documentElement.classList.remove('collapsed');
            document.documentElement.classList.add('expanded');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/vendors/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/vendors/tabler-icons/tabler-icons.min.css') }}">

    @stack('styles-before')

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/caria-brand.css') }}">

    @stack('styles')
</head>
<body>
    {{-- Scroll progress bar --}}
    <div class="ca-scroll-progress" aria-hidden="true"></div>

    <div>
        <!-- Sidebar -->
        @include('components.dashboard-sidebar')

        <!-- Main Content -->
        <div id="content" class="position-relative h-100">
            <!-- Topbar -->
            @include('components.dashboard-topbar')

            <!-- Page Content -->
            <div class="custom-container">
                @hasSection('page-heading')
                    <div class="ca-page-heading">
                        @hasSection('page-eyebrow')
                            <div class="ca-eyebrow mb-2">@yield('page-eyebrow')</div>
                        @endif
                        <h1>@yield('page-heading')</h1>
                        @hasSection('page-sub')
                            <p>@yield('page-sub')</p>
                        @endif
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Notification Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNotification">
        <div class="sticky-top bg-body">
            <div class="offcanvas-header gap-4">
                <div class="d-flex justify-content-between w-100">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
        </div>
        <div class="offcanvas-body p-4">
            <p class="text-secondary">No notifications yet.</p>
        </div>
    </div>

    <!-- Vendor JS -->
    <script src="{{ asset('dashboard/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/simplebar/simplebar.min.js') }}"></script>

    @stack('scripts')

    <!-- Theme JS -->
    <script src="{{ asset('dashboard/js/vendors/sidebarnav.js') }}"></script>
    <script src="{{ asset('dashboard/js/main.js') }}"></script>
    <script src="{{ asset('dashboard/js/caria-dashboard.js') }}"></script>

    @include('components.toast-notifications')
</body>
</html>
