@extends('layouts.dashboard')

@section('title', 'Recommended Courses')
@section('page-eyebrow', '§ Recommended Courses')
@section('page-heading', 'Curated YouTube courses for your gaps.')
@section('page-sub', 'Hand-picked tutorials targeting the exact skills you need to close.')

@section('content')

    <div class="mb-4">
        <p class="text-secondary mb-0">
            <strong>{{ number_format($totalCount) }}</strong> total courses across {{ $availableSkills->count() }} skills
        </p>
    </div>

    {{-- Filter card --}}
    @if($totalCount > 0)
        <div class="ca-card mb-4">
            <form method="GET" action="{{ route('user.courses.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6 col-12">
                    <label class="form-label small text-secondary mb-1">Search</label>
                    <div class="position-relative">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y" style="left: 12px; color: var(--bs-secondary-color);"></i>
                        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Course title or channel…" style="padding-left: 38px;">
                    </div>
                </div>
                <div class="col-lg-4 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Skill</label>
                    <select name="skill" class="form-select">
                        <option value="" @selected($skill === '')>All skills</option>
                        @foreach($availableSkills as $s)
                            <option value="{{ $s }}" @selected($skill === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                    <label class="form-label small text-secondary mb-1">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="newest" @selected($sort === 'newest')>Newest</option>
                        <option value="skill"  @selected($sort === 'skill')>Group by skill</option>
                        <option value="alpha"  @selected($sort === 'alpha')>Alphabetical</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn ca-glow-btn">
                        <i class="ti ti-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('user.courses.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Reset
                    </a>
                    <span class="ms-auto text-secondary small align-self-center">
                        @if($grouped)
                            Grouped view · {{ is_object($courses) && method_exists($courses, 'flatten') ? $courses->flatten()->count() : 0 }} courses
                        @else
                            Showing <strong>{{ $courses->firstItem() ?? 0 }}</strong>–<strong>{{ $courses->lastItem() ?? 0 }}</strong> of <strong>{{ $courses->total() }}</strong>
                        @endif
                    </span>
                </div>
            </form>
        </div>
    @endif

    @php
        $hasResults = $grouped ? $courses->count() > 0 : $courses->count() > 0;
    @endphp

    @if($hasResults)

        @if($grouped)
            {{-- Grouped view (by skill) --}}
            @foreach($courses as $skillName => $skillCourses)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-6">
                            <i class="ti ti-bulb me-1"></i> {{ $skillName }}
                        </span>
                        <span class="text-secondary small">{{ $skillCourses->count() }} {{ Str::plural('course', $skillCourses->count()) }}</span>
                    </div>
                    <div class="row g-4">
                        @foreach($skillCourses as $course)
                            @include('user.courses.partials.card', ['course' => $course])
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            {{-- Flat paginated view --}}
            <div class="row g-4">
                @foreach($courses as $course)
                    @include('user.courses.partials.card', ['course' => $course])
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        @endif

    @else
        <div class="row justify-content-center">
            <div class="col-xl-6 col-12">
                <div class="ca-card">
                    <div class="ca-empty-state">
                        @if($totalCount > 0)
                            <div class="ico"><i class="ti ti-filter-off"></i></div>
                            <h4>No Courses Match</h4>
                            <p class="text-secondary">Try adjusting or resetting your filters.</p>
                            <a href="{{ route('user.courses.index') }}" class="btn btn-outline-primary mt-2">
                                <i class="ti ti-x me-1"></i> Reset Filters
                            </a>
                        @else
                            <div class="ico"><i class="ti ti-school"></i></div>
                            <h4>No Courses Yet</h4>
                            <p class="text-secondary">Upload your CV to get personalized course recommendations based on your skill gaps.</p>
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

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
