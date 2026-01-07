@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-glass">
            
            <div class="header mb-4">
                <h3 class="text-warning"><i class="bi bi-folder-plus me-2"></i>Add New Category</h3>
            </div>

            {{-- رسالة الخطأ العامة --}}
            @if(session('error'))
                <div class="alert alert-danger bg-danger text-white border-0 shadow-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-bold text-white">Category Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control bg-dark text-white border-secondary focus-ring focus-ring-warning" 
                           value="{{ old('name') }}" 
                           placeholder="Enter category name">

                    {{-- عرض أخطاء التحقق --}}
                    @error('name')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-3d px-4">
                        <i class="bi bi-check-circle me-1"></i>Save Category
                    </button>
                    
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-light px-4">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection