@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('header')
    <h1 class="mb-1">Forgot Password</h1>
    <p class="mb-0 text-secondary">No worries, we will send you reset instructions.</p>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Reset Password</button>
        </div>
    </form>
    <div class="text-center">
        <a href="{{ route('login') }}">Back to Login</a>
    </div>
@endsection
