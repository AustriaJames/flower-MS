@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center" style="background: #5D2B4C; color: #fff;">
                    <h4 class="mb-0">Forgot Password</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-4 text-muted text-center">
                        Enter your email address and we will send you a link to reset your password.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn" style="background: #5D2B4C; color: #fff;">
                                Send Password Reset Link
                            </button>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #5D2B4C;">Back to login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
