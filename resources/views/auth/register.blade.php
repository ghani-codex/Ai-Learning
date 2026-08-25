@extends('layouts.auth')

@section('title', 'Sign Up')

@section('header')
    <h1 class="mb-1">Sign Up</h1>
    <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary">Log in</a></p>
@endsection

@section('content')
    <form method="POST" action="{{ route('register') }}" class="mb-6">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="password-field position-relative">
                <input type="password" class="form-control fakePassword @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="password-field position-relative">
                <input type="password" class="form-control fakePassword" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                <span><i class="ti ti-eye-off passwordToggler"></i></span>
            </div>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Sign Up</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/js/vendors/password.js') }}"></script>
@endpush
