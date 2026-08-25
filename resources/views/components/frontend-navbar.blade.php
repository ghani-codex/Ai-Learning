<nav class="ca-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            {{-- Logo / wordmark --}}
            <a href="{{ route('home') }}" class="ca-brand">
                <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="IntelliCareer logo">
                <span>IntelliCareer</span>
            </a>

            {{-- Desktop nav --}}
            <ul class="ca-nav-links">
                <li><a href="#hero">Home</a></li>
                <li><a href="#problem">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#team">Team</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            {{-- Right cluster: theme toggle + auth CTA --}}
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="ca-theme-toggle" type="button" data-bs-toggle="dropdown" aria-label="Toggle theme">
                        <i class="ca-theme-icon bi bi-circle-half"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end ca-theme-menu">
                        <li><button class="dropdown-item" data-ca-theme="light" type="button"><i class="bi bi-sun-fill"></i> Light</button></li>
                        <li><button class="dropdown-item" data-ca-theme="dark" type="button"><i class="bi bi-moon-stars-fill"></i> Dark</button></li>
                        <li><button class="dropdown-item" data-ca-theme="auto" type="button"><i class="bi bi-circle-half"></i> Auto</button></li>
                    </ul>
                </div>

                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="ca-btn ca-btn-primary d-none d-sm-inline-flex">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="ca-btn ca-btn-ghost d-none d-md-inline-flex">Sign In</a>
                    <a href="{{ route('register') }}" class="ca-btn ca-btn-primary d-none d-sm-inline-flex">Get Started</a>
                @endauth

                {{-- Mobile menu toggle --}}
                <button class="ca-mobile-toggle" type="button" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- Mobile menu overlay --}}
<div class="ca-mobile-menu">
    <a href="#hero">Home</a>
    <a href="#problem">About</a>
    <a href="#features">Features</a>
    <a href="#how-it-works">How It Works</a>
    <a href="#team">Team</a>
    <a href="#contact">Contact</a>
    <hr style="border-color: var(--ca-border);">
    @auth
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="ca-btn ca-btn-primary">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="ca-btn ca-btn-ghost">Sign In</a>
        <a href="{{ route('register') }}" class="ca-btn ca-btn-primary">Get Started</a>
    @endauth
</div>
