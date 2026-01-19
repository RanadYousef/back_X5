@extends('layouts.admin')

@section('content')
<style>
.card-modern {
    background: #0f0f0f;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid rgba(255,234,0,0.12);
}
.btn-neon, .neon-btn {
    color: #ffea00;
    border: 1px solid #ffea00;
    background: transparent;
    transition: all 0.2s ease-in-out;
}
.btn-neon i, .neon-btn i { color: white; }
.btn-neon:hover, .neon-btn:hover {
    background: rgba(255,234,0,0.05);
    box-shadow: 0 0 6px #ffea00;
    transform: translateY(-1px);
}
.neon-active {
    background: #ffea00 !important;
    box-shadow: 0 0 12px #ffea00;
}
.neon-active i { color: white !important; }
.table-dark { border-radius: 10px; overflow: hidden; }
.table-dark tbody tr { transition: background 0.2s ease, transform 0.2s ease; }
.table-dark tbody tr:hover { background-color: rgba(255,234,0,0.06) !important; transform: scale(1.01); }
.table-dark thead th { border-bottom: 1px solid rgba(255,234,0,0.2); }
</style>

<div class="card-modern">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-white mb-0">
            <i class="bi bi-people-fill"></i> User Management
        </h4>
        <a href="{{ route('users.create') }}" class="btn btn-neon btn-sm neon-clickable">
            <i class="bi bi-plus-circle"></i> Add User
        </a>
    </div>

    <!-- جدول المستخدمين العاديين -->
    <h5 class="text-white mb-2">Active Users</h5>
    <table class="table table-dark table-hover align-middle mb-5">
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
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning btn-sm neon-btn neon-clickable">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-warning btn-sm neon-btn neon-clickable"
                                onclick="return confirm('Delete user?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- جدول المستخدمين المحذوفين -->
    <h5 class="text-white mb-2">Deleted Users</h5>
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
        @foreach($deletedUsers as $user)
            <tr class="table-danger">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-danger text-dark">
                        {{ $user->roles->pluck('name')->first() }}
                    </span>
                </td>
                <td class="text-center"><form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-success btn-sm neon-btn neon-clickable"
                                onclick="return confirm('Restore user?')">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>
                    <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm neon-btn neon-clickable"
                                onclick="return confirm('Permanently delete user?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.neon-clickable').forEach(btn => {
    btn.addEventListener('click', function(e) {
        document.querySelectorAll('.neon-clickable').forEach(b => b.classList.remove('neon-active'));
        e.currentTarget.classList.add('neon-active');
    });
});
</script>
@endsection