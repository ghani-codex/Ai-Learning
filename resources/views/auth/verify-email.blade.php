@extends('layouts.auth')

@section('title', 'Verify Email')

@section('header')
    <h1 class="mb-1">Verify Email</h1>
    <p class="mb-0 text-secondary">Please verify your email address by clicking on the link we just emailed to you.</p>
@endsection

@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">A new verification link has been sent to the email address you provided during registration.</div>
    @endif

    <div class="d-flex justify-content-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Log Out</button>
        </form>
    </div>
@endsection
