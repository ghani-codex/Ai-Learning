@extends('layouts.dashboard')

@section('title', 'Account Settings')
@section('page-heading', 'Account Settings')

@section('content')
    <!-- Profile Information -->
    <div class="row g-6 mb-6">
        <div class="col-xl-4 col-12">
            <h5 class="mb-1">Profile Information</h5>
            <p class="text-secondary small">Update your account's profile information and email address.</p>
        </div>
        <div class="col-xl-8 col-12">
            <div class="card card-lg">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Update Password -->
    <div class="row g-6 mb-6">
        <div class="col-xl-4 col-12">
            <h5 class="mb-1">Update Password</h5>
            <p class="text-secondary small">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div class="col-xl-8 col-12">
            <div class="card card-lg">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="row g-6 mb-6">
        <div class="col-xl-4 col-12">
            <h5 class="mb-1">Delete Account</h5>
            <p class="text-secondary small">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
        <div class="col-xl-8 col-12">
            <div class="card card-lg">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
