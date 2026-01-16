@extends('layouts.admin')

@section('content')
    <div class="card-glass p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning m-0">
                <i class="bi bi-book-half"></i> Books Collection
            </h2>

            <div class="d-flex gap-2">
                <a href="{{ route('books.create') }}" class="btn btn-3d">
                    <i class="bi bi-plus-circle"></i> Add Book
                </a>
                <a href="{{ route('books.trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Trash Bin
                </a>
            </div>
        </div>

        <form action="{{ route('books.index') }}" method="GET" id="filterForm" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-warning border-warning">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-dark text-white border-warning"
                            placeholder="Search by title or author..." value="{{ request('search') }}">
                        <button class="btn btn-warning text-dark fw-bold" type="submit">Search</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-warning border-warning">
                            <i class="bi bi-filter"></i>
                        </span>
                        <select name="category_id" class="form-select bg-dark text-white border-warning"
                            onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary w-100">Clear Filters</a>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-3">
            <table class="table table-dark table-dark-custom table-hover align-middle text-center">
                <thead>
                    <tr class="text-warning border-bottom border-warning">
                        <th class="py-3">Cover</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Description</th>
                        <th class="py-3">Author</th>
                        <th class="py-3">Details</th> 
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td>
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                        class="rounded shadow border border-secondary" width="50" height="70"
                                        style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-inline-block shadow-sm"
                                        style="width: 50px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image text-white-50"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="fw-bold">{{ $book->title }}</td>

                            <td>
                                <span class="badge bg-outline-warning border border-warning text-warning px-3 py-2">
                                    {{ $book->category->name ?? '—' }}
                                </span>
                            </td>

                            <td style="max-width: 200px;">
                                <span class="text-white-50 small" data-bs-toggle="tooltip" title="{{ $book->description }}">
                                    {{ \Illuminate\Support\Str::limit($book->description, 50) }}
                                </span>
                            </td>

                            <td>{{ $book->author }}</td>

                            <td>
                                <div class="small text-muted mb-1">{{ $book->publish_year }} | {{ $book->language }}</div>
                                <span class="badge bg-success px-2">{{ $book->copies_number }} Copies</span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form method="POST" action="{{ route('books.destroy', $book) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-5 fs-5">
                                <i class="bi bi-exclamation-circle d-block mb-2 fs-2"></i>
                                No books found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

    <style>
        .table-dark-custom {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-3d {
            background: linear-gradient(145deg, #ffc107, #e0a800);
            color: #000;
            font-weight: bold;
            border: none;
            transition: transform 0.2s;
        }

        .btn-3d:hover {
            transform: translateY(-2px);
            background: #ffc107;
        }

        .badge.bg-outline-warning {
            background-color: transparent;
        }
    </style>
@endsection