@extends('layouts.auth')

@section('content')

<h5 class="text-warning mb-3 fw-bold">
    <i class="bi bi-box-arrow-in-right"></i> Login
</h5>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3 text-start">
        <label class="text-light small">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3 text-start">
        <label class="text-light small">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-main w-100 mt-3">
        Login
    </button>

    <p class="text-muted mt-4 small">
        Don’t have an account?
        <a href="{{ route('register') }}" class="text-warning fw-bold">
            Register
        </a>
    </p>
</form>

@endsection
