@extends('layouts.dashboard')

@section('title', 'User Management')
@section('page-eyebrow', '§ User Management')
@section('page-heading', 'Manage Users')
@section('page-sub', 'Total ' . number_format($totalCount) . ' registered users · Search, filter, edit and remove accounts.')

@section('content')

    {{-- Filters --}}
    <div class="ca-card mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4 col-12">
                <label class="form-label small text-secondary mb-1">Search</label>
                <div class="position-relative">
                    <i class="ti ti-search position-absolute top-50 translate-middle-y" style="left: 12px; color: var(--bs-secondary-color);"></i>
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Name or email…" style="padding-left: 38px;">
                </div>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-secondary mb-1">Role</label>
                <select name="role" class="form-select">
                    <option value="all"   @selected($role === 'all')>All roles</option>
                    <option value="user"  @selected($role === 'user')>User</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-secondary mb-1">Verified</label>
                <select name="verified" class="form-select">
                    <option value="all" @selected($verified === 'all')>Any</option>
                    <option value="yes" @selected($verified === 'yes')>Verified</option>
                    <option value="no"  @selected($verified === 'no')>Unverified</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-secondary mb-1">CV</label>
                <select name="cv" class="form-select">
                    <option value="all"      @selected($cv === 'all')>Any</option>
                    <option value="uploaded" @selected($cv === 'uploaded')>Uploaded</option>
                    <option value="none"     @selected($cv === 'none')>None</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label small text-secondary mb-1">Sort</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected($sort === 'newest')>Newest</option>
                    <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                    <option value="name"   @selected($sort === 'name')>Name (A–Z)</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn ca-glow-btn">
                    <i class="ti ti-filter me-1"></i> Apply
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-x me-1"></i> Reset
                </a>
                <span class="ms-auto text-secondary small align-self-center">
                    Showing <strong>{{ $users->firstItem() ?? 0 }}</strong>–<strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong>
                </span>
            </div>
        </form>
    </div>

    {{-- Users table --}}
    <div class="ca-card">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th>CV</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="ca-user-avatar">
                                            {{ strtoupper(mb_substr($u->name, 0, 1)) }}{{ strtoupper(mb_substr(explode(' ', $u->name)[1] ?? '', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-bold text-truncate" style="max-width: 240px;">
                                                {{ $u->name }}
                                                @if($u->id === auth()->id())
                                                    <span class="badge bg-primary-subtle text-primary ms-1" style="font-size: 10px;">YOU</span>
                                                @endif
                                            </div>
                                            <div class="text-secondary small text-truncate" style="max-width: 240px;">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $u->role === 'admin' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' }}">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($u->email_verified_at)
                                        <span class="badge bg-success-subtle text-success"><i class="ti ti-check"></i> Verified</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary"><i class="ti ti-x"></i> Unverified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($u->cvProfile)
                                        <span class="badge bg-info-subtle text-info">
                                            @if($u->cvProfile->status === 'parsed')
                                                <i class="ti ti-circle-check"></i> Parsed
                                            @elseif(in_array($u->cvProfile->status, ['pending', 'parsing']))
                                                <i class="ti ti-loader-2"></i> Processing
                                            @else
                                                <i class="ti ti-alert-triangle"></i> {{ ucfirst($u->cvProfile->status) }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-secondary small">—</span>
                                    @endif
                                </td>
                                <td class="text-secondary small">{{ $u->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        @if($u->id !== auth()->id())
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal"
                                                    data-user-id="{{ $u->id }}"
                                                    data-user-name="{{ $u->name }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @else
            <div class="ca-empty-state">
                <div class="ico"><i class="ti ti-users-off"></i></div>
                <h4>No Users Match</h4>
                <p class="text-secondary">Try adjusting or resetting your filters.</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary mt-2">Reset Filters</a>
            </div>
        @endif
    </div>

    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="deleteUserForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1">Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                        <p class="text-secondary small mb-0">This will permanently remove their CV, job matches, skill gaps, and recommended courses. This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i> Delete User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .ca-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--ca-gradient);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: var(--ca-font-heading);
        font-weight: 700;
        color: #fff;
        font-size: 14px;
        flex-shrink: 0;
    }
    .pagination { --bs-pagination-color: var(--bs-body-color); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('deleteUserModal');
    if (!modal) return;
    modal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        const userId = trigger.getAttribute('data-user-id');
        const userName = trigger.getAttribute('data-user-name');
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserForm').action = '{{ url('admin/users') }}/' + userId;
    });
})();
</script>
@endpush
