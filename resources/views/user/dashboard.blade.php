@extends('layouts.dashboard')

@section('title', 'User Dashboard')
@section('page-eyebrow', '§ Dashboard')
@section('page-heading', 'Welcome back, ' . Auth::user()->name)
@section('page-sub', 'Monitor your career journey, jobs, and learning progress.')

@section('content')
    {{-- Quick-action banner (no duplicate greeting — page heading already does that) --}}
    <div class="ca-welcome-banner mb-6">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="ca-eyebrow mb-2">§ Quick Actions</div>
                <h2 class="mb-2" style="font-size: 1.4rem;">Your AI co-pilot is ready.</h2>
                <p class="mb-4" style="max-width: 460px;">Track real-time job matches, identify skill gaps, and discover the exact courses you need to grow.</p>
                <div class="d-flex flex-wrap gap-2">
                    @if(!$cvProfile)
                        <a href="{{ route('user.cv.index') }}" class="btn btn-light">
                            <i class="ti ti-upload me-1"></i> Upload Your CV
                        </a>
                    @else
                        <a href="{{ route('user.jobs.index') }}" class="btn btn-light">
                            <i class="ti ti-briefcase me-1"></i> View Job Matches
                        </a>
                        <a href="{{ route('user.cv.index') }}" class="btn btn-outline-light">
                            <i class="ti ti-refresh me-1"></i> Update CV
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ca-welcome-status-card">
                    @if($cvProfile && $cvProfile->isParsed())
                        <div class="lbl">CV Status</div>
                        <div class="d-flex align-items-center gap-3">
                            <x-ring-progress
                                :value="min(100, count($cvProfile->parsed_data['skills'] ?? []) * 8)"
                                :size="64"
                                :stroke="5"
                                color="primary"
                                :label="count($cvProfile->parsed_data['skills'] ?? []) . ''" />
                            <div class="flex-grow-1 min-w-0">
                                <div class="val">CV Analyzed</div>
                                <div class="sub text-truncate">{{ $cvProfile->parsed_data['target_niche'] ?? 'Profile complete' }}</div>
                            </div>
                        </div>
                    @elseif($cvProfile && ($cvProfile->isPending() || $cvProfile->isParsing()))
                        <div class="lbl">CV Status</div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="spinner-border" role="status" style="color: #c4b5fd;"></div>
                            <div>
                                <div class="val">Processing...</div>
                                <div class="sub">This may take a moment</div>
                            </div>
                        </div>
                    @else
                        <div class="lbl">Get Started</div>
                        <div class="val">No CV Yet</div>
                        <div class="sub">Upload to unlock matches</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row row-cols-1 row-cols-xl-3 row-cols-md-3 mb-6 g-6">
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                            <i class="ti ti-briefcase fs-4"></i>
                        </div>
                        <div>Job Matches</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold">{{ $jobsCount }}</div>
                        <a href="{{ route('user.jobs.index') }}" class="text-primary small">View all</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                            <i class="ti ti-chart-bar fs-4"></i>
                        </div>
                        <div>Skill Gaps</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold">{{ $gapsCount }}</div>
                        <a href="{{ route('user.skill-gaps.index') }}" class="text-primary small">View all</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ca-card ca-stat-card">
                <div class="d-flex flex-column gap-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                            <i class="ti ti-school fs-4"></i>
                        </div>
                        <div>Courses</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center lh-1">
                        <div class="fs-3 fw-bold">{{ $coursesCount }}</div>
                        <a href="{{ route('user.courses.index') }}" class="text-primary small">View all</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Job Matches --}}
    <div class="row g-6 mb-6">
        <div class="col-12">
            <div class="ca-card">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Recent Job Matches</h5>
                        @if($recentJobs->count() > 0)
                            <a href="{{ route('user.jobs.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                        @endif
                    </div>
                    @if($recentJobs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Location</th>
                                        <th>Match</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentJobs as $job)
                                        <tr>
                                            <td class="fw-bold">{{ $job->job_title }}</td>
                                            <td>{{ $job->company_name }}</td>
                                            <td>{{ $job->location ?? 'N/A' }}</td>
                                            <td>
                                                <x-ring-progress :value="$job->match_score" :size="44" :stroke="4" />
                                            </td>
                                            <td>
                                                <a href="{{ $job->job_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    Apply
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="ti ti-briefcase fs-1 text-secondary mb-2 d-block"></i>
                            <p class="text-secondary mb-3">No job matches yet. Upload your CV to get started!</p>
                            <a href="{{ route('user.cv.index') }}" class="btn btn-primary btn-sm">Upload CV</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
