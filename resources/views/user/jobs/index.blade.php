@extends('layouts.dashboard')

@section('title', 'Job Matches')
@section('page-eyebrow', '§ Job Matches')
@section('page-heading', 'Real-time matches, ranked.')
@section('page-sub', 'We hit live job APIs and score every result against your profile.')

@section('content')
    {{-- Header with refresh --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <p class="text-secondary mb-0">
            <strong>{{ number_format($totalCount) }}</strong> total matching jobs in your profile
        </p>
        @if($cvProfile && $cvProfile->parsed_data)
            <form action="{{ route('user.jobs.refresh') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="ti ti-refresh me-1"></i> Refresh Jobs
                </button>
            </form>
        @endif
    </div>

    {{-- Filter card --}}
    @if($totalCount > 0)
        <div class="ca-card mb-4">
            <form method="GET" action="{{ route('user.jobs.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label small text-secondary mb-1">Search</label>
                    <div class="position-relative">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y" style="left: 12px; color: var(--bs-secondary-color);"></i>
                        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Job title or company…" style="padding-left: 38px;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Match Score</label>
                    <select name="match" class="form-select">
                        <option value="all"    @selected($match === 'all')>Any</option>
                        <option value="high"   @selected($match === 'high')>High (70%+)</option>
                        <option value="medium" @selected($match === 'medium')>Medium (40–69%)</option>
                        <option value="low"    @selected($match === 'low')>Low (&lt;40%)</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Type</label>
                    <select name="type" class="form-select">
                        <option value="all"        @selected($type === 'all')>Any</option>
                        <option value="fulltime"   @selected($type === 'fulltime')>Full-time</option>
                        <option value="parttime"   @selected($type === 'parttime')>Part-time</option>
                        <option value="contractor" @selected($type === 'contractor')>Contractor</option>
                        <option value="intern"     @selected($type === 'intern')>Intern</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Location</label>
                    <input type="text" name="location" value="{{ $location }}" class="form-control" placeholder="e.g. Remote, US">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="match"   @selected($sort === 'match')>Best match</option>
                        <option value="newest"  @selected($sort === 'newest')>Newest</option>
                        <option value="oldest"  @selected($sort === 'oldest')>Oldest</option>
                        <option value="company" @selected($sort === 'company')>Company A–Z</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn ca-glow-btn">
                        <i class="ti ti-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('user.jobs.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Reset
                    </a>
                    <span class="ms-auto text-secondary small align-self-center">
                        Showing <strong>{{ $jobs->firstItem() ?? 0 }}</strong>–<strong>{{ $jobs->lastItem() ?? 0 }}</strong> of <strong>{{ $jobs->total() }}</strong>
                    </span>
                </div>
            </form>
        </div>
    @endif

    @if($jobs->count() > 0)
        <div class="row g-4">
            @foreach($jobs as $job)
                <div class="col-xl-6 col-12">
                    <div class="ca-card ca-job-card h-100 d-flex flex-column">
                        {{-- Row 1: company + location pill --}}
                        <div class="ca-job-meta-top">
                            <i class="ti ti-building"></i>
                            <strong style="color: var(--bs-body-color);">{{ $job->company_name }}</strong>
                            @if($job->location)
                                <span class="text-secondary">·</span>
                                <span><i class="ti ti-map-pin"></i> {{ $job->location }}</span>
                            @endif
                        </div>

                        {{-- Row 2: full-width title (can wrap) --}}
                        <h5 class="ca-job-title">{{ $job->job_title }}</h5>

                        {{-- Row 3: info + ring --}}
                        <div class="ca-job-row">
                            <div class="ca-job-info">
                                @if($job->employment_type)
                                    <span><i class="ti ti-clock"></i> {{ $job->employment_type }}</span>
                                @endif
                                @if($job->salary)
                                    <span><i class="ti ti-currency-dollar"></i> {{ $job->salary }}</span>
                                @endif
                            </div>
                            <x-ring-progress :value="$job->match_score" :size="58" :stroke="5" />
                        </div>

                        {{-- Skill chips --}}
                        @if($job->skills_matched && count($job->skills_matched) > 0)
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach(array_slice($job->skills_matched, 0, 6) as $skill)
                                    <span class="ca-job-skill">{{ $skill }}</span>
                                @endforeach
                                @if(count($job->skills_matched) > 6)
                                    <span class="ca-job-skill" style="opacity: 0.7;">+{{ count($job->skills_matched) - 6 }} more</span>
                                @endif
                            </div>
                        @endif

                        {{-- Apply button --}}
                        <div class="mt-auto pt-2">
                            <a href="{{ $job->job_url }}" target="_blank" class="btn ca-glow-btn w-100">
                                <i class="ti ti-external-link me-1"></i> Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="row justify-content-center">
            <div class="col-xl-6 col-12">
                <div class="ca-card">
                    <div class="ca-empty-state">
                        @if($totalCount > 0)
                            <div class="ico"><i class="ti ti-filter-off"></i></div>
                            <h4>No Jobs Match Your Filters</h4>
                            <p class="text-secondary">Try adjusting or resetting your filters.</p>
                            <a href="{{ route('user.jobs.index') }}" class="btn btn-outline-primary mt-2">
                                <i class="ti ti-x me-1"></i> Reset Filters
                            </a>
                        @else
                            <div class="ico"><i class="ti ti-briefcase-off"></i></div>
                            <h4>No Job Matches Yet</h4>
                            <p class="text-secondary">Upload your CV or fill your profile to find matching jobs.</p>
                            <a href="{{ route('user.cv.index') }}" class="btn btn-primary mt-2">
                                <i class="ti ti-file-cv me-1"></i> Upload CV
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
