@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('header')
    <h1 class="mb-1">Confirm Password</h1>
    <p class="mb-0 text-secondary">This is a secure area. Please confirm your password before continuing.</p>
@endsection

@section('content')
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-field position-relative">
                <input type="password" class="form-control fakePassword @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Confirm</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/js/vendors/password.js') }}"></script>
@endpush
