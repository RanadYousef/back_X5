@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card-glass">
            
            <div class="header mb-4 pb-3 border-bottom border-secondary text-warning">
                <h3><i class="bi bi-shield-plus me-2"></i>Create New Role</h3>
            </div>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold text-white">Role Name</label>
                    <input type="text" 
                           name="name" 
                           class="form-control bg-dark text-white border-secondary focus-ring focus-ring-warning" 
                           value="{{ old('name') }}" 
                           placeholder="e.g. Content Manager">
                    @error('name') 
                        <div class="text-danger small mt-2">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-warning border-bottom border-warning mb-3">
                        <i class="bi bi-check2-all me-1"></i> Assign Permissions
                    </label>
                    
                    <div class="row g-3 p-3 rounded bg-dark border border-secondary shadow-inner">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check form-switch p-2 rounded hover-effect">
                                <input class="form-check-input ms-0 me-2" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->name }}" 
                                       id="p-{{ $permission->id }}">
                                <label class="form-check-label text-light fs-6" for="p-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-secondary my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-3d px-5">
                        <i class="bi bi-cloud-upload me-1"></i> Save Role
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
    .hover-effect:hover {
        background: rgba(245, 158, 11, 0.1);
        transition: 0.3s;
    }
    
    .shadow-inner {
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);
    }
</style>
@endsection