@extends('layouts.admin')

@section('content')
<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <h3 class="text-warning mb-0">
            <i class="bi bi-tags me-2"></i>Categories List
        </h3>

        @can('manage categories')
            <a href="{{ route('categories.create') }}" class="btn btn-3d">
                <i class="bi bi-plus-lg me-1"></i>Add New Category
            </a>
            <a href="{{ route('categories.trash') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i> Trash Bin
            </a>
        @endcan
    </div>
    
    <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-dark text-warning border-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control bg-dark text-white border-secondary"
                       placeholder="Search category by name...">
            </div>
        </div>

        <div class="col-md-2">
            <button class="btn btn-outline-warning w-100">
                Search
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100">
                Clear
            </a>
            </div>
        </div>
    </form>




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
                    <th width="10%">ID</th>
                    <th>Category Name</th>
                    <th width="20%">Books Count</th>
                    <th width="25%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-secondary">
                        <td class="text-secondary">#{{ $category->id }}</td>
                        <td>
                            <span class="fw-bold text-light">{{ $category->name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-warning text-warning px-3 py-2">
                                <i class="bi bi-book me-1"></i>{{ $category->books_count }} Books
                            </span>
                        </td>
                        <td>
                            <div class="actions d-flex justify-content-center gap-2">
                                <a href="{{ route('categories.show', $category->id) }}" 
                                   class="btn btn-outline-info btn-sm shadow-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                @can('manage categories')
                                    <a href="{{ route('categories.edit', $category->id) }}" 
                                       class="btn btn-outline-warning btn-sm shadow-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection