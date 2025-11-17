@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header border-0 text-center" style="background: #F5EEE4; border-radius: 20px 20px 0 0;">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Welcome Back</h5>
                </div>

                <div class="card-body p-4">

                    {{-- Status Message --}}
                    @if (session('status'))
                        <div class="alert alert-info mb-3">{{ session('status') }}</div>
                    @endif

                    {{-- Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <strong>There was a problem with your login:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- MAIN LOGIN FORM --}}
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        {{-- Email Field --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #5D2B4C;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-envelope" style="color: #5D2B4C;"></i>
                                </span>
                                <input type="email"
                                       class="form-control border-0 @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus
                                       style="background: #F5EEE4; color: #5D2B4C;"
                                       placeholder="Enter your email">
                            </div>
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="color: #5D2B4C;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-lock" style="color: #5D2B4C;"></i>
                                </span>
                                <input type="password"
                                       class="form-control border-0 @error('password') is-invalid @enderror"
                                       id="password" name="password" required
                                       style="background: #F5EEE4; color: #5D2B4C;"
                                       placeholder="Enter your password">
                            </div>
                        </div>

                        {{-- Remember + Forgot --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember" style="color: #5D2B4C;">
                                    Remember Me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}" style="color: #5D2B4C;">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        {{-- Login Button --}}
                        <div class="d-grid mb-3">
                            <button type="submit"
                                    class="btn fw-semibold text-white"
                                    style="background: #5D2B4C; border-radius: 12px;">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>

      
                    {{-- SIGN UP LINK --}}
                    @if (Route::has('register'))
                        <div class="text-center mt-3">
                            <span style="color: #5D2B4C;">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="fw-bold" style="color: #5D2B4C;">
                                Sign Up
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
