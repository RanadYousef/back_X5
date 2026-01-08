@extends('layouts.auth')

@section('content')

<h5 class="text-warning mb-3 fw-bold">
    <i class="bi bi-person-plus"></i> Register
</h5>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-2 text-start">
        <label class="text-light small">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-2 text-start">
        <label class="text-light small">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-2 text-start">
        <label class="text-light small">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-2 text-start">
        <label class="text-light small">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button class="btn btn-main w-100 mt-3">
        Register
    </button>

    <p class="text-muted mt-4 small">
        Already have an account?
        <a href="{{ route('login') }}" class="text-warning fw-bold">
            Login
        </a>
    </p>
</form>

@endsection
