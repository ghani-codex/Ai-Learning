<div class="col-xl-4 col-md-6 col-12">
    <div class="ca-card h-100 overflow-hidden p-0" style="display: flex; flex-direction: column;">
        @if($course->thumbnail_url)
            <a href="{{ $course->youtube_url }}" target="_blank" rel="noopener">
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->course_title }}" style="width: 100%; height: 180px; object-fit: cover; display: block;">
            </a>
        @endif
        <div class="d-flex flex-column flex-grow-1" style="padding: 1.1rem 1.25rem 1.25rem;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="ca-job-skill" style="font-size: 10.5px;"><i class="ti ti-bulb me-1"></i>{{ $course->skill_target }}</span>
            </div>
            <h6 class="mb-2 line-clamp-2" style="font-size: 0.95rem; line-height: 1.4;">{{ $course->course_title }}</h6>
            @if($course->channel_name)
                <p class="text-secondary small mb-3">
                    <i class="ti ti-brand-youtube" style="color: #ef4444;"></i> {{ $course->channel_name }}
                </p>
            @endif
            <div class="mt-auto">
                <a href="{{ $course->youtube_url }}" target="_blank" rel="noopener" class="btn btn-danger btn-sm w-100">
                    <i class="ti ti-player-play me-1"></i> Watch on YouTube
                </a>
            </div>
        </div>
    </div>
</div>
