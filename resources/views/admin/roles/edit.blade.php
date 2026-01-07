@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card-glass shadow-lg">
            
            <div class="header mb-4 pb-3 border-bottom border-secondary text-warning d-flex align-items-center">
                <i class="bi bi-shield-shaded fs-3 me-2"></i>
                <h3 class="mb-0">Edit Role: <span class="text-white">{{ $role->name }}</span></h3>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf 
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-white">Role Name</label>
                    <input type="text" 
                           name="name" 
                           class="form-control bg-dark text-white border-secondary focus-ring focus-ring-warning" 
                           value="{{ old('name', $role->name) }}"
                           placeholder="Enter role name">
                    @error('name') 
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-warning border-bottom border-warning mb-3">
                        <i class="bi bi-list-check me-1"></i> Permissions Management
                    </label>
                    
                    <div class="row g-3 p-4 rounded bg-dark bg-opacity-50 border border-secondary shadow-inner">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check form-switch p-2 rounded permission-card">
                                <input class="form-check-input ms-0 me-2" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->name }}" 
                                       id="p-{{ $permission->id }}"
                                       @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                <label class="form-check-label text-light" for="p-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-secondary my-4">

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-3d px-5">
                        <i class="bi bi-arrow-clockwise me-1"></i> Update Role
                    </button>
                    
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-light px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .permission-card {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .permission-card:hover {
        background: rgba(245, 158, 11, 0.15); 
        border-color: rgba(245, 158, 11, 0.3);
        transform: translateY(-2px);
    }

    .shadow-inner {
        box-shadow: inset 0 0 15px rgba(0,0,0,0.6);
    }

    .form-check-input:checked {
        background-color: #f59e0b;
        border-color: #fbbf24;
    }
</style>
@endsection