@extends('layouts.admin')

@section('content')
<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <h3 class="text-warning mb-0">
            <i class="bi bi-shield-lock me-2"></i>Roles & Permissions
        </h3>
        <a href="{{ route('roles.create') }}" class="btn btn-3d">
            <i class="bi bi-plus-circle me-1"></i> New Role
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger bg-danger text-white border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle border-secondary">
            <thead>
                <tr class="text-warning">
                    <th width="20%">Role Name</th>
                    <th width="55%">Permissions</th>
                    <th width="25%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr class="border-secondary">
                        <td>
                            <strong class="text-white text-uppercase">{{ $role->name }}</strong>
                        </td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-dark border border-warning text-warning fw-light m-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-key-fill me-1" style="font-size: 0.65rem;"></i>{{ $permission->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('roles.edit', $role->id) }}" 
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>

                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection