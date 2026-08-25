@extends('layouts.dashboard')

@section('title', 'My CV')
@section('page-eyebrow', '§ My CV')
@section('page-heading', 'Your career profile.')
@section('page-sub', 'Upload your CV or fill the form — our AI extracts your skills and target role.')

@section('content')
    @if(!$cvProfile || $cvProfile->isFailed() || request()->has('reupload'))
        {{-- Upload / Manual Form --}}
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="card card-lg">
                    <div class="card-body">
                        @if($cvProfile && $cvProfile->isFailed())
                            <div class="alert alert-danger mb-4">
                                <i class="ti ti-alert-circle me-2"></i>
                                CV processing failed. Please try again or use the manual form.
                            </div>
                        @endif

                        <ul class="nav nav-tabs mb-4" id="cvTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button" role="tab">
                                    <i class="ti ti-upload me-1"></i> Upload PDF
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual-pane" type="button" role="tab">
                                    <i class="ti ti-forms me-1"></i> Manual Entry
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="cvTabsContent">
                            {{-- Upload Tab --}}
                            <div class="tab-pane fade show active" id="upload-pane" role="tabpanel">
                                <form action="{{ route('user.cv.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="border border-dashed rounded-3 p-8 text-center mb-4" id="dropZone"
                                         style="border-color: var(--bs-primary); cursor: pointer;"
                                         onclick="document.getElementById('cv_file').click()">
                                        <i class="ti ti-cloud-upload fs-1 text-primary mb-3 d-block"></i>
                                        <h5>Drag & Drop your CV here</h5>
                                        <p class="text-secondary mb-2">or click to browse</p>
                                        <small class="text-muted">PDF only, max 5MB</small>
                                        <input type="file" class="d-none" id="cv_file" name="cv_file" accept=".pdf">
                                        <div id="fileName" class="mt-3 text-primary fw-bold d-none"></div>
                                    </div>
                                    @error('cv_file')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="ti ti-upload me-2"></i> Upload & Analyze CV
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Manual Tab --}}
                            <div class="tab-pane fade" id="manual-pane" role="tabpanel">
                                <form action="{{ route('user.cv.manual') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Skills <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('skills') is-invalid @enderror" name="skills" value="{{ old('skills') }}" placeholder="PHP, Laravel, JavaScript, React, MySQL..." required>
                                            <small class="text-muted">Separate skills with commas</small>
                                            @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Target Role <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('target_niche') is-invalid @enderror" name="target_niche" value="{{ old('target_niche') }}" placeholder="e.g. Full Stack Developer" required>
                                            @error('target_niche')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Experience (years) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" max="50" required>
                                            @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Preferred Location</label>
                                            <input type="text" class="form-control" name="preferred_location" value="{{ old('preferred_location') }}" placeholder="e.g. Remote, New York, Pakistan">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Education Level</label>
                                            <select class="form-select" name="education_level">
                                                <option value="">Select...</option>
                                                <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>High School</option>
                                                <option value="Diploma" {{ old('education_level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                                <option value="Bachelor's" {{ old('education_level') == "Bachelor's" ? 'selected' : '' }}>Bachelor's</option>
                                                <option value="Master's" {{ old('education_level') == "Master's" ? 'selected' : '' }}>Master's</option>
                                                <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Professional Summary</label>
                                            <textarea class="form-control" name="summary" rows="3" placeholder="Brief summary of your professional background...">{{ old('summary') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="ti ti-device-floppy me-2"></i> Save & Find Jobs
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($cvProfile->isPending() || $cvProfile->isParsing())
        {{-- Processing State (true indeterminate ring) --}}
        <div class="row justify-content-center">
            <div class="col-xl-6 col-12">
                <div class="ca-card">
                    <div class="text-center py-6">
                        <div class="ca-cv-loader mb-4">
                            <svg viewBox="0 0 140 140" aria-hidden="true">
                                <defs>
                                    <linearGradient id="caRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#6366f1"/>
                                        <stop offset="50%" stop-color="#8b5cf6"/>
                                        <stop offset="100%" stop-color="#06b6d4"/>
                                    </linearGradient>
                                </defs>
                                <circle class="track" cx="70" cy="70" r="60"></circle>
                                <circle class="arc" cx="70" cy="70" r="60"></circle>
                            </svg>
                            <div class="ca-cv-loader-label">Processing</div>
                        </div>
                        <h4 class="mb-2">Analyzing your <span class="ca-gradient-text">CV</span>...</h4>
                        <p class="text-secondary mb-0">We're parsing your profile, fetching matches, and curating courses.</p>
                        <p class="text-muted small mt-4 mb-0" id="statusText" style="transition: opacity 0.2s ease;">
                            <i class="ti ti-loader-2 me-1"></i> Parsing CV...
                        </p>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Parsed Profile --}}
        <div class="row g-6 mb-6">
            <div class="col-xl-8 col-12">
                <div class="card card-lg">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Profile Summary</h5>
                            <div class="d-flex gap-2">
                                <form action="{{ route('user.cv.refresh') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-refresh me-1"></i> Refresh
                                    </button>
                                </form>
                            </div>
                        </div>

                        @php $data = $cvProfile->parsed_data; @endphp

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary">
                                        <i class="ti ti-user fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary">Name</small>
                                        <div class="fw-bold">{{ $data['name'] ?? Auth::user()->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                                        <i class="ti ti-target fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary">Target Role</small>
                                        <div class="fw-bold">{{ $data['target_niche'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                                        <i class="ti ti-clock fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary">Experience</small>
                                        <div class="fw-bold">{{ $data['experience_years'] ?? 0 }} years</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                                        <i class="ti ti-map-pin fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary">Location</small>
                                        <div class="fw-bold">{{ $data['preferred_location'] ?? 'Not specified' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Skills --}}
                        <h6 class="mt-4 mb-3">Skills ({{ count($data['skills'] ?? []) }})</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(($data['skills'] ?? []) as $skill)
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $skill }}</span>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        @if(!empty($data['summary']))
                            <h6 class="mt-4 mb-2">Summary</h6>
                            <p class="text-secondary">{{ $data['summary'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Side Stats --}}
            <div class="col-xl-4 col-12">
                <div class="card card-lg mb-4">
                    <div class="card-body text-center">
                        <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success mx-auto mb-3">
                            <i class="ti ti-briefcase fs-4"></i>
                        </div>
                        <h3 class="fw-bold">{{ Auth::user()->jobMatches()->count() }}</h3>
                        <p class="text-secondary mb-0">Jobs Found</p>
                        <a href="{{ route('user.jobs.index') }}" class="btn btn-outline-success btn-sm mt-3">View Jobs</a>
                    </div>
                </div>
                <div class="card card-lg mb-4">
                    <div class="card-body text-center">
                        <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning mx-auto mb-3">
                            <i class="ti ti-chart-bar fs-4"></i>
                        </div>
                        <h3 class="fw-bold">{{ Auth::user()->skillGaps()->count() }}</h3>
                        <p class="text-secondary mb-0">Skill Gaps</p>
                        <a href="{{ route('user.skill-gaps.index') }}" class="btn btn-outline-warning btn-sm mt-3">View Gaps</a>
                    </div>
                </div>
                <div class="card card-lg">
                    <div class="card-body text-center">
                        <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info mx-auto mb-3">
                            <i class="ti ti-school fs-4"></i>
                        </div>
                        <h3 class="fw-bold">{{ Auth::user()->recommendedCourses()->count() }}</h3>
                        <p class="text-secondary mb-0">Courses</p>
                        <a href="{{ route('user.courses.index') }}" class="btn btn-outline-info btn-sm mt-3">View Courses</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Re-upload option --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center py-3">
                        <span class="text-secondary"><i class="ti ti-info-circle me-1"></i> Want to update your profile? Upload a new CV or edit manually.</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.cv.index') }}?reupload=1" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-upload me-1"></i> Re-upload CV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // File name display
    document.getElementById('cv_file')?.addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        if (fileName) {
            const el = document.getElementById('fileName');
            el.textContent = fileName;
            el.classList.remove('d-none');
        }
    });

    // Drag and drop
    const dropZone = document.getElementById('dropZone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(e => {
            dropZone.addEventListener(e, function(ev) {
                ev.preventDefault();
                this.style.backgroundColor = 'rgba(var(--bs-primary-rgb), 0.05)';
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            dropZone.addEventListener(e, function(ev) {
                ev.preventDefault();
                this.style.backgroundColor = '';
            });
        });
        dropZone.addEventListener('drop', function(ev) {
            const file = ev.dataTransfer.files[0];
            if (file) {
                document.getElementById('cv_file').files = ev.dataTransfer.files;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileName').classList.remove('d-none');
            }
        });
    }

    // Auto-refresh status when processing
    @if($cvProfile && ($cvProfile->isPending() || $cvProfile->isParsing()))
        const statusCheck = setInterval(async () => {
            try {
                const res = await fetch('{{ route("user.cv.status") }}');
                const data = await res.json();
                if (data.status === 'parsed' || data.status === 'failed') {
                    clearInterval(statusCheck);
                    window.location.reload();
                }
                if (data.status === 'parsing') {
                    document.getElementById('statusText').textContent = 'Analyzing skills and finding jobs...';
                }
            } catch(e) {}
        }, 3000);
    @endif
</script>

