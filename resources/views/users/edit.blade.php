@extends('layouts.admin')

@section('content')

<div class="card-glass col-md-6 mx-auto">
    <h4 class="mb-4">
        <i class="bi bi-person-gear text-warning"></i>
        Edit User
    </h4>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name"
                   value="{{ $user->name }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   value="{{ $user->email }}"
                   class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm">Back</a>
            <button class="btn btn-3d btn-sm">Update</button>
        </div>
    </form>
</div>

@endsection
