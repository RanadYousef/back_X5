@extends('layouts.admin')

@section('content')

<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-people-fill text-warning"></i>
            User Management
        </h4>

        <a href="{{ route('users.create') }}" class="btn btn-3d btn-sm">
            <i class="bi bi-plus-circle"></i> Add User
        </a>
    </div>

    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-warning text-dark">
                        {{ $user->roles->pluck('name')->first() }}
                    </span>
                </td>
                <td class="text-center">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('users.destroy', $user) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Delete user?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection
