@extends('layouts.admin')

@section('content')

<style>
.user-row {
    transition: all .3s ease;
}
.user-row:hover {
    background: rgba(255,193,7,.08);
    transform: scale(1.01);
}
.user-deleted {
    opacity: .55;
}
.user-email {
    color: #e5e7eb;
    font-weight: 500;
}
.action-btn {
    transition: .25s;
}
.action-btn:hover {
    transform: translateY(-2px) scale(1.05);
}
</style>

<div class="card-glass">
    <div class="d-flex justify-content-between mb-4">
        <h4 class="text-warning">
            <i class="bi bi-people-fill"></i> User Management
        </h4>
        <a href="{{ route('users.create') }}" class="btn btn-3d btn-sm">
            <i class="bi bi-plus-circle"></i> Add User
        </a>
    </div>

    <table class="table table-dark align-middle">
        <thead class="text-warning">
        <tr>
            <th>#</th><th>Name</th><th>Email</th><th>Role</th><th class="text-center">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
        <tr class="user-row {{ $user->trashed() ? 'user-deleted' : '' }}">
            <td>{{ $loop->iteration }}</td>
            <td class="text-light fw-semibold">{{ $user->name }}</td>
            <td class="user-email">{{ $user->email }}</td>
            <td>
                <span class="badge bg-warning text-dark">
                    {{ $user->roles->pluck('name')->first() }}
                </span>
            </td>
            <td class="text-center">

                @if(!$user->trashed())
                    <a href="{{ route('users.edit',$user) }}"
                       class="btn btn-outline-info btn-sm action-btn">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form method="POST"
                          action="{{ route('users.destroy',$user) }}"
                          class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm action-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @else
                    <form method="POST"
                          action="{{ route('users.restore',$user->id) }}"
                          class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-success btn-sm action-btn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('users.forceDelete',$user->id) }}"
                          class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm action-btn">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </form>
                @endif

            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection