@extends('layouts.admin')

@section('content')

<div class="card-glass col-md-6 mx-auto">
    <h4 class="mb-4">
        <i class="bi bi-person-plus-fill text-warning"></i>
        Create User
    </h4>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm">Cancel</a>
            <button class="btn btn-3d btn-sm">Create</button>
        </div>
    </form>
</div>

@endsection
