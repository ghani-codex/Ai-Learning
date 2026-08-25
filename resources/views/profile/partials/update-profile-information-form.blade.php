<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<div class="row g-4 align-items-start flex-row-reverse">
    {{-- Ring avatar (right column on desktop, top on mobile) --}}
    <div class="col-lg-4 col-12">
        <div class="ca-avatar-wrap text-center">
            <div class="ca-avatar-ring">
                <img src="{{ $user->profile_image_url }}"
                     alt="{{ $user->name }}"
                     class="ca-avatar-img"
                     id="ca-avatar-preview">

                <form method="POST" action="{{ route('profile.avatar.update') }}"
                      enctype="multipart/form-data" id="ca-avatar-form" class="d-none">
                    @csrf
                    <input type="file" name="profile_image" id="ca-avatar-input"
                           accept="image/jpeg,image/png,image/webp">
                </form>

                <button type="button" class="ca-avatar-edit-btn"
                        onclick="document.getElementById('ca-avatar-input').click()"
                        aria-label="Change profile photo">
                    <i class="ti ti-camera"></i>
                </button>
            </div>

            <div class="mt-3 d-flex flex-column align-items-center gap-2">
                <button type="button" class="btn btn-sm ca-glow-btn"
                        onclick="document.getElementById('ca-avatar-input').click()">
                    <i class="ti ti-upload me-1"></i> Upload photo
                </button>

                @if($user->profile_image)
                    <form method="POST" action="{{ route('profile.avatar.delete') }}"
                          onsubmit="return confirm('Remove your profile photo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                            <i class="ti ti-trash me-1"></i> Remove photo
                        </button>
                    </form>
                @endif

                <p class="small text-secondary mb-0 mt-1" style="font-size: 0.75rem;">
                    JPG, PNG or WEBP · max 2 MB
                </p>
            </div>

            @error('profile_image')
                <div class="alert alert-danger mt-3 py-2 px-3 small mb-0">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Name + email form --}}
    <div class="col-lg-8 col-12">
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $user->name) }}"
                       required autofocus autocomplete="name">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $user->email) }}"
                       required autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="small text-secondary">
                            Your email address is unverified.
                            <button form="send-verification" class="btn btn-link btn-sm p-0 text-primary">Click here to re-send the verification email.</button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="small text-success mt-1">A new verification link has been sent to your email address.</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Save</button>

                @if (session('status') === 'profile-updated')
                    <span class="small text-success">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>

@once
    @push('styles')
    <style>
        .ca-avatar-wrap {
            padding: 0.5rem 0;
        }

        /* Outer gradient ring with glow */
        .ca-avatar-ring {
            position: relative;
            display: inline-block;
            padding: 4px;
            border-radius: 50%;
            background: var(--ca-gradient);
            box-shadow: 0 12px 36px -10px rgba(99, 102, 241, 0.45);
        }
        .ca-avatar-ring::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            background: var(--ca-gradient);
            opacity: 0.25;
            filter: blur(12px);
            z-index: -1;
        }

        .ca-avatar-img {
            width: 132px;
            height: 132px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: var(--bs-body-bg);
            border: 4px solid var(--bs-body-bg);
        }

        /* Floating camera button at bottom-right of the ring */
        .ca-avatar-edit-btn {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 3px solid var(--bs-body-bg);
            background: var(--ca-gradient);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .ca-avatar-edit-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 10px 24px rgba(99, 102, 241, 0.5);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        (function () {
            const input   = document.getElementById('ca-avatar-input');
            const form    = document.getElementById('ca-avatar-form');
            const preview = document.getElementById('ca-avatar-preview');
            if (!input || !form || !preview) return;

            input.addEventListener('change', function () {
                if (!input.files || !input.files[0]) return;

                const file = input.files[0];

                // Quick client-side guard rails so we don't post junk.
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    if (typeof window.showToast === 'function') {
                        window.showToast('error', 'Invalid file', 'Please choose a JPG, PNG, or WEBP image.');
                    }
                    input.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    if (typeof window.showToast === 'function') {
                        window.showToast('error', 'File too large', 'Maximum size is 2 MB.');
                    }
                    input.value = '';
                    return;
                }

                // Optimistic preview, then submit.
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; };
                reader.readAsDataURL(file);

                form.submit();
            });
        })();
    </script>
    @endpush
@endonce
