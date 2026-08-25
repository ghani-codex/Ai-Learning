<!-- Navbar -->
<div class="navbar-glass navbar navbar-expand-lg px-0 px-lg-4">
    <div class="container-fluid px-lg-0">
        <div class="d-flex align-items-center gap-4">
            <!-- Mobile menu toggle -->
            <div class="d-block d-lg-none">
                <a class="text-inherit" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 6l16 0"/><path d="M4 12l16 0"/><path d="M4 18l16 0"/>
                    </svg>
                </a>
            </div>
            <!-- Desktop sidebar collapse -->
            <div class="d-none d-lg-block">
                <a class="sidebar-toggle d-flex texttooltip p-3" href="javascript:void(0)" data-template="collapseMessage">
                    <span class="collapse-mini">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-bar-left text-secondary">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 12l10 0"/><path d="M4 12l4 4"/><path d="M4 12l4 -4"/><path d="M20 4l0 16"/>
                        </svg>
                    </span>
                    <span class="collapse-expanded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-bar-right text-secondary">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 12l-10 0"/><path d="M20 12l-4 4"/><path d="M20 12l-4 -4"/><path d="M4 4l0 16"/>
                        </svg>
                        <div id="collapseMessage" class="d-none"><span class="small">Collapse</span></div>
                    </span>
                </a>
            </div>
        </div>

        <!-- Right side items -->
        <ul class="list-unstyled d-flex align-items-center mb-0 gap-2">
            <!-- Dark/Light mode toggle -->
            <li>
                <div class="dropdown">
                    <button class="btn btn-ghost btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme">
                        <i class="ti theme-icon-active lh-1 fs-5"><i class="ti theme-icon ti-sun"></i></i>
                        <span class="visually-hidden bs-theme-text">Toggle theme</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true"><i class="ti theme-icon ti ti-sun"></i><span class="ms-2">Light</span></button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false"><i class="ti theme-icon ti-moon-stars"></i><span class="ms-2">Dark</span></button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false"><i class="ti theme-icon ti-circle-half-2"></i><span class="ms-2">Auto</span></button></li>
                    </ul>
                </div>
            </li>
            <!-- Notifications bell -->
            <li>
                <a class="position-relative btn-icon btn-ghost btn rounded-circle" data-bs-toggle="offcanvas" href="#offcanvasNotification" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
                    </svg>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">2<span class="visually-hidden">unread messages</span></span>
                </a>
            </li>
            <!-- User dropdown -->
            <li class="ms-3 dropdown">
                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" class="avatar avatar-sm rounded-circle" style="object-fit: cover;">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
                    <div>
                        <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-4 py-4">
                            <img src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" class="avatar avatar-md rounded-circle" style="object-fit: cover;">
                            <div>
                                <h4 class="mb-0 fs-5">{{ Auth::user()->name }}</h4>
                                <p class="mb-0 text-secondary small">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column gap-1">
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="dropdown-item d-flex align-items-center gap-2">
                                <i class="ti ti-home-2 fs-5"></i><span>Dashboard</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item d-flex align-items-center gap-2">
                                <i class="ti ti-settings fs-5"></i><span>Account Settings</span>
                            </a>
                        </div>
                        <div class="border-dashed border-top mb-4 pt-4 px-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="text-secondary d-flex align-items-center gap-2"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="ti ti-login-2 fs-5"></i><span>Logout</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
