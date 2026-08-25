@extends('layouts.auth')

@section('title', 'Sign In')

@section('header')
    <h1 class="mb-1">Welcome Back</h1>
    <p class="mb-0">Don't have an account yet? <a href="{{ route('register') }}" class="text-primary">Register here</a></p>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mb-6">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-field position-relative">
                <input type="password" class="form-control fakePassword @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-primary">Forgot Password</a>
            @endif
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Sign In</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/js/vendors/password.js') }}"></script>
@endpush
