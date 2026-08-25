@extends('layouts.dashboard')

@section('title', 'Skill Gaps')
@section('page-eyebrow', '§ Skill Gaps')
@section('page-heading', 'Skills you can improve.')
@section('page-sub', 'Importance is calculated from real signal in your matched jobs — focus on the highest-priority gaps first.')

@section('content')
    <div class="mb-4">
        <p class="text-secondary mb-0">
            <strong>{{ number_format($totalCount) }}</strong> total skill gaps detected from your CV
        </p>
    </div>

    {{-- Filter card --}}
    @if($totalCount > 0)
        <div class="ca-card mb-4">
            <form method="GET" action="{{ route('user.skill-gaps.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6 col-12">
                    <label class="form-label small text-secondary mb-1">Search</label>
                    <div class="position-relative">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y" style="left: 12px; color: var(--bs-secondary-color);"></i>
                        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Skill name…" style="padding-left: 38px;">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="all"    @selected($priority === 'all')>Any</option>
                        <option value="high"   @selected($priority === 'high')>High (8–10)</option>
                        <option value="medium" @selected($priority === 'medium')>Medium (5–7)</option>
                        <option value="low"    @selected($priority === 'low')>Low (1–4)</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="priority"     @selected($sort === 'priority')>Priority (highest first)</option>
                        <option value="priority-asc" @selected($sort === 'priority-asc')>Priority (lowest first)</option>
                        <option value="alpha"        @selected($sort === 'alpha')>Alphabetical</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn ca-glow-btn">
                        <i class="ti ti-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('user.skill-gaps.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Reset
                    </a>
                    <span class="ms-auto text-secondary small align-self-center">
                        Showing <strong>{{ $skillGaps->firstItem() ?? 0 }}</strong>–<strong>{{ $skillGaps->lastItem() ?? 0 }}</strong> of <strong>{{ $skillGaps->total() }}</strong>
                    </span>
                </div>
            </form>
        </div>
    @endif

    @if($skillGaps->count() > 0)
        <div class="row g-4">
            @foreach($skillGaps as $gap)
                <div class="col-xl-6 col-12">
                    <div class="ca-card h-100">
                        <div>
                            <div class="d-flex align-items-start gap-4 mb-3">
                                @php
                                    $pct = $gap->importance * 10;
                                    $ringColor = $gap->importance >= 8 ? 'danger' : ($gap->importance >= 5 ? 'warning' : 'primary');
                                @endphp
                                <x-ring-progress :value="$pct" :size="72" :stroke="6" :color="$ringColor" :label="$gap->importance . '/10'" />

                                <div class="flex-grow-1 min-w-0">
                                    <h5 class="mb-1">{{ $gap->skill_name }}</h5>
                                    <small class="text-secondary">
                                        Priority: <strong class="text-{{ $ringColor === 'primary' ? 'info' : $ringColor }}">{{ $gap->importance >= 8 ? 'High' : ($gap->importance >= 5 ? 'Medium' : 'Low') }}</strong>
                                    </small>
                                </div>
                            </div>

                            <p class="text-secondary small mb-3">{{ $gap->reason }}</p>

                            <a href="{{ route('user.courses.index') }}?skill={{ urlencode($gap->skill_name) }}" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-school me-1"></i> Find Courses
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $skillGaps->links() }}
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-xl-6 col-12">
                <div class="ca-card">
                    <div class="ca-empty-state">
                        @if($totalCount > 0)
                            <div class="ico"><i class="ti ti-filter-off"></i></div>
                            <h4>No Skill Gaps Match</h4>
                            <p class="text-secondary">Try adjusting or resetting your filters.</p>
                            <a href="{{ route('user.skill-gaps.index') }}" class="btn btn-outline-primary mt-2">
                                <i class="ti ti-x me-1"></i> Reset Filters
                            </a>
                        @else
                            <div class="ico"><i class="ti ti-chart-bar-off"></i></div>
                            <h4>No Skill Gaps Detected</h4>
                            <p class="text-secondary">Upload your CV first to analyze your skills against job requirements.</p>
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
