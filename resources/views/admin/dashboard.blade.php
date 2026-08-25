@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-eyebrow', '§ Admin Dashboard')
@section('page-heading', 'Welcome back, ' . Auth::user()->name)
@section('page-sub', 'Manage your platform, users, and system health.')

@section('content')
    {{-- Quick-action banner (no duplicate greeting — page heading already does that) --}}
    <div class="ca-welcome-banner mb-6">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="ca-eyebrow mb-2">§ Admin Tools</div>
                <h2 class="mb-2" style="font-size: 1.4rem;">Manage your platform.</h2>
                <p class="mb-4" style="max-width: 460px;">Monitor users, content, and platform health from this admin hub.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-light">
                        <i class="ti ti-users me-1"></i> Manage Users
                    </a>
                    <a href="#" class="btn btn-outline-light">
                        <i class="ti ti-settings me-1"></i> System Settings
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ca-welcome-status-card">
                    <div class="lbl">System Status</div>
                    <div class="d-flex align-items-center gap-3">
                        <x-ring-progress :value="100" :size="64" :stroke="5" color="success" label="OK" />
                        <div class="flex-grow-1 min-w-0">
                            <div class="val">All Systems Operational</div>
                            <div class="sub">No incidents reported</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 1: User stats --}}
    <div class="row row-cols-1 row-cols-xl-3 row-cols-md-3 mb-6 g-6">
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary">
                            <i class="ti ti-users fs-4"></i>
                        </div>
                        <div>Total Users</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['total_users']) }}</div>
                        <a href="{{ route('admin.users.index') }}" class="text-primary small">Manage</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                            <i class="ti ti-user-check fs-4"></i>
                        </div>
                        <div>Verified Users</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['verified_users']) }}</div>
                        <div class="text-secondary small">
                            @if($stats['total_users'] > 0)
                                {{ round(($stats['verified_users'] / $stats['total_users']) * 100) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                            <i class="ti ti-user-plus fs-4"></i>
                        </div>
                        <div>New This Month</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['new_this_month']) }}</div>
                        <div class="text-secondary small">{{ now()->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Platform activity stats --}}
    <div class="row row-cols-1 row-cols-xl-3 row-cols-md-3 mb-6 g-6">
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                            <i class="ti ti-file-cv fs-4"></i>
                        </div>
                        <div>CVs Parsed</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['cvs_parsed']) }}</div>
                        <div class="text-secondary small">of {{ number_format($stats['cvs_uploaded']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                            <i class="ti ti-briefcase fs-4"></i>
                        </div>
                        <div>Jobs Matched</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['jobs_matched']) }}</div>
                        <div class="text-secondary small">total</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                            <i class="ti ti-school fs-4"></i>
                        </div>
                        <div>Courses Curated</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold ca-mono">{{ number_format($stats['courses_curated']) }}</div>
                        <div class="text-secondary small">YouTube</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
