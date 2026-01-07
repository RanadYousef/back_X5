@extends('layouts.admin')

@section('content')
<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <h3 class="text-warning mb-0">
            <i class="bi bi-info-circle me-2"></i>Category: {{ $category->name }}
        </h3>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="p-3 rounded bg-dark border border-secondary shadow-sm text-center">
                <span class="text-secondary d-block small">Category ID</span>
                <h4 class="text-white mb-0">#{{ $category->id }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded bg-dark border border-warning shadow-sm text-center">
                <span class="text-secondary d-block small">Total Books</span>
                <h4 class="text-warning mb-0">{{ $books->total() }} <span class="small fs-6">Books</span></h4>
            </div>
        </div>
    </div>

    <h5 class="text-light mb-3"><i class="bi bi-book me-2"></i>Books in this Category</h5>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle border-secondary">
            <thead class="text-warning">
                <tr>
                    <th width="10%">#ID</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr class="border-secondary">
                        <td class="text-secondary">{{ $book->id }}</td>
                        <td><strong class="text-white">{{ $book->title }}</strong></td>
                        <td class="text-light">{{ $book->author ?? 'Unknown' }}</td>
                        <td class="text-center">
                            @if($book->is_available)
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Available
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Borrowed
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No books found in this category.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4 custom-pagination">
        {{ $books->links() }}
    </div>
</div>

<style>
    .custom-pagination .pagination {
        --bs-pagination-bg: rgba(15, 23, 42, 0.5);
        --bs-pagination-border-color: rgba(255, 255, 255, 0.1);
        --bs-pagination-color: #f59e0b;
        --bs-pagination-hover-bg: #f59e0b;
        --bs-pagination-hover-color: #020617;
        --bs-pagination-active-bg: #f59e0b;
        --bs-pagination-active-border-color: #f59e0b;
    }
</style>
@endsection