@extends('layouts.dashboard')

@section('title', 'Edit User · ' . $user->name)
@section('page-eyebrow', '§ Edit User')
@section('page-heading', $user->name)
@section('page-sub', $user->email)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-secondary text-decoration-none">
            <i class="ti ti-arrow-left me-1"></i> Back to all users
        </a>
    </div>

    <div class="row g-4 mb-6">
        {{-- LEFT: Edit form --}}
        <div class="col-xl-7 col-12">
            <div class="ca-card">
                <h5 class="mb-1">Account Details</h5>
                <p class="text-secondary small mb-4">Update name, email, role and verification status.</p>

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    @disabled($user->id === auth()->id())>
                                <option value="user"  @selected(old('role', $user->role) === 'user')>User</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            </select>
                            @if($user->id === auth()->id())
                                <div class="form-text"><i class="ti ti-info-circle"></i> You can't change your own role.</div>
                            @endif
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Verification</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="verified" name="verified" value="1"
                                       @checked(old('verified', $user->email_verified_at !== null))>
                                <label class="form-check-label" for="verified">
                                    Mark as verified
                                </label>
                            </div>
                            @if($user->email_verified_at)
                                <div class="form-text small">Verified on {{ $user->email_verified_at->format('M d, Y') }}</div>
                            @else
                                <div class="form-text small">Currently unverified</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn ca-glow-btn">
                            <i class="ti ti-device-floppy me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>

            {{-- Danger zone --}}
            @if($user->id !== auth()->id())
                <div class="ca-card mt-4" style="border-color: rgba(239, 68, 68, 0.3);">
                    <h5 class="mb-1" style="color: #ef4444;">Danger Zone</h5>
                    <p class="text-secondary small mb-3">Permanently delete this user account and all their data.</p>
                    <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        <i class="ti ti-trash me-1"></i> Delete User Account
                    </button>
                </div>
            @endif
        </div>

        {{-- RIGHT: Summary / read-only stats --}}
        <div class="col-xl-5 col-12">
            <div class="ca-card mb-4">
                <h5 class="mb-3">Account Summary</h5>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-secondary small">User ID</span>
                        <span class="ca-mono small">#{{ $user->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-secondary small">Joined</span>
                        <span class="small">{{ $user->created_at->format('M d, Y · g:i A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-secondary small">Last Updated</span>
                        <span class="small">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary small">Status</span>
                        @if($user->email_verified_at)
                            <span class="badge bg-success-subtle text-success"><i class="ti ti-check"></i> Verified</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Unverified</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ca-card mb-4">
                <h5 class="mb-3">Career Profile</h5>
                @if($user->cvProfile)
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                            <span class="text-secondary small">CV Status</span>
                            @if($user->cvProfile->status === 'parsed')
                                <span class="badge bg-success-subtle text-success">Parsed</span>
                            @elseif(in_array($user->cvProfile->status, ['pending', 'parsing']))
                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">{{ ucfirst($user->cvProfile->status) }}</span>
                            @endif
                        </div>
                        @if($user->cvProfile->isParsed())
                            <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                                <span class="text-secondary small">Target Role</span>
                                <span class="small text-truncate" style="max-width: 60%;">{{ $user->cvProfile->parsed_data['target_niche'] ?? '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                                <span class="text-secondary small">Skills Detected</span>
                                <span class="ca-mono small">{{ count($user->cvProfile->parsed_data['skills'] ?? []) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2" style="border-color: var(--bs-border-color) !important;">
                                <span class="text-secondary small">Experience</span>
                                <span class="small">{{ $user->cvProfile->parsed_data['experience_years'] ?? 0 }} years</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary small">Uploaded</span>
                            <span class="small">{{ $user->cvProfile->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-secondary small mb-0"><i class="ti ti-file-off me-1"></i> No CV uploaded yet.</p>
                @endif
            </div>

            <div class="ca-card">
                <h5 class="mb-3">Activity</h5>
                <div class="row g-3">
                    <div class="col-4 text-center">
                        <div class="ca-mono fs-3 fw-bold text-primary">{{ $user->job_matches_count }}</div>
                        <div class="text-secondary small">Job Matches</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="ca-mono fs-3 fw-bold" style="color: #f59e0b;">{{ $user->skill_gaps_count }}</div>
                        <div class="text-secondary small">Skill Gaps</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="ca-mono fs-3 fw-bold" style="color: #06b6d4;">{{ $user->recommended_courses_count }}</div>
                        <div class="text-secondary small">Courses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete modal --}}
    @if($user->id !== auth()->id())
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Delete <strong>{{ $user->name }}</strong>?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-1">This will permanently remove this user account.</p>
                            <p class="text-secondary small mb-0">All their CV data, job matches, skill gaps, and recommended courses will also be deleted. This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="ti ti-trash me-1"></i> Yes, Delete Permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection
