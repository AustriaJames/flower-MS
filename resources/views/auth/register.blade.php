@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header border-0 text-center" style="background: #F5EEE4; border-radius: 20px 20px 0 0;">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Create Account</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-semibold" style="color: #5D2B4C;">First Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input id="first_name" type="text" class="form-control border-0 @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your first name">
                            </div>
                            @error('first_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-semibold" style="color: #5D2B4C;">Last Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input id="last_name" type="text" class="form-control border-0 @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your last name">
                            </div>
                            @error('last_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #5D2B4C;">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-envelope" style="color: #5D2B4C;"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your email">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold" style="color: #5D2B4C;">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4; color: #5D2B4C;">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input id="phone" type="text" class="form-control border-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your phone number">
                            </div>
                            @error('phone')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="color: #5D2B4C;">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-lock" style="color: #5D2B4C;"></i>
                                </span>
                                <input id="password" type="password" class="form-control border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Create a password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold" style="color: #5D2B4C;">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-lock" style="color: #5D2B4C;"></i>
                                </span>
                                <input id="password_confirmation" type="password" class="form-control border-0" name="password_confirmation" required autocomplete="new-password" style="background: #F5EEE4; color: #5D2B4C;" placeholder="Confirm your password">
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>
                        </div>

                        <div class="text-center">
                            <span style="color: #5D2B4C;">Already have an account? </span>
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #5D2B4C;">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
