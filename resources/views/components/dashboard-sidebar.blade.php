<!-- Desktop Sidebar -->
<div id="miniSidebar">
    <div class="brand-logo">
        <a class="d-none d-md-flex align-items-center gap-2" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}">
            <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="IntelliCareer" width="32" height="32">
            <span class="ca-logo-text site-logo-text">IntelliCareer</span>
        </a>
    </div>
    <ul class="navbar-nav flex-column">
        @foreach($menuItems as $item)
            @if(isset($item['type']) && $item['type'] === 'heading')
                <li class="nav-item">
                    <div class="nav-heading">{{ $item['name'] }}</div>
                    <hr class="mx-5 nav-line mb-1">
                </li>
            @elseif(isset($item['submenu']))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="nav-icon"><i class="ti ti-{{ $item['icon'] }}" style="font-size: 20px;"></i></span>
                        <span class="text">{{ $item['name'] }}</span>
                    </a>
                    <ul class="dropdown-menu flex-column">
                        @foreach($item['submenu'] as $sub)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $sub['url'] }}">{{ $sub['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs($item['active'] ?? '') ? 'active' : '' }}" href="{{ $item['url'] }}">
                        <span class="nav-icon"><i class="ti ti-{{ $item['icon'] }}" style="font-size: 20px;"></i></span>
                        <span class="text">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<!-- Mobile Offcanvas Sidebar -->
<div class="offcanvasNav offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample">
    <div class="offcanvas-header">
        <a class="d-flex align-items-center gap-2" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}">
            <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="IntelliCareer" width="32" height="32">
            <span class="ca-logo-text site-logo-text">IntelliCareer</span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="navbar-nav flex-column">
            @foreach($menuItems as $item)
                @if(isset($item['type']) && $item['type'] === 'heading')
                    <li class="nav-item">
                        <div class="nav-heading">{{ $item['name'] }}</div>
                        <hr class="mx-5 nav-line mb-1">
                    </li>
                @elseif(isset($item['submenu']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="nav-icon"><i class="ti ti-{{ $item['icon'] }}" style="font-size: 20px;"></i></span>
                            <span class="text">{{ $item['name'] }}</span>
                        </a>
                        <ul class="dropdown-menu flex-column">
                            @foreach($item['submenu'] as $sub)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $sub['url'] }}">{{ $sub['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($item['active'] ?? '') ? 'active' : '' }}" href="{{ $item['url'] }}">
                            <span class="nav-icon"><i class="ti ti-{{ $item['icon'] }}" style="font-size: 20px;"></i></span>
                            <span class="text">{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
