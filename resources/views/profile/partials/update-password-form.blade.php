<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">Current Password</label>
        <input type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" id="update_password_current_password" name="current_password" autocomplete="current-password">
        @if($errors->updatePassword->has('current_password'))
            <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
        @endif
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">New Password</label>
        <input type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" id="update_password_password" name="password" autocomplete="new-password">
        @if($errors->updatePassword->has('password'))
            <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
        @endif
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Save</button>

        @if (session('status') === 'password-updated')
            <span class="small text-success">Saved.</span>
        @endif
    </div>
</form>
