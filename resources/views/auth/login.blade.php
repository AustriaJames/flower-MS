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
                    @if (session('status'))
                        <div class="alert alert-info mb-3">{{ session('status') }}</div>
                    @endif

                    @if (session('url.intended'))
                        <div class="alert alert-info mb-3">
                            Please log in to continue.
                        </div>
                    @endif

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

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #5D2B4C;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-envelope" style="color: #5D2B4C;"></i>
                                </span>
                                <input type="email" class="form-control border-0 @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your email">
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="color: #5D2B4C;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #F5EEE4;">
                                    <i class="fas fa-lock" style="color: #5D2B4C;"></i>
                                </span>
                                <input type="password" class="form-control border-0 @error('password') is-invalid @enderror"
                                       id="password" name="password" required autocomplete="current-password"
                                       style="background: #F5EEE4; color: #5D2B4C;" placeholder="Enter your password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember" style="color: #5D2B4C;">
                                    Remember Me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}" style="color: #5D2B4C;">
                                    Forgot Your Password?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn fw-semibold text-white" style="background: #5D2B4C; border-radius: 12px;">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
