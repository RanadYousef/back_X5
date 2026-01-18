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
                    <th class="py-3">Rating</th>
                    <th class="py-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($books as $book)
                <tr class="book-row">
                    <td>
                        @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}"
                            class="rounded shadow border border-secondary book-cover"
                            width="50" height="70"
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
                        <div class="small text-white mb-1">{{ $book->publish_year }} | {{ $book->language }}</div>
                        <span class="badge bg-success px-2">{{ $book->copies_number }} Copies</span>
                    </td>

                    <td>
                        <div class="d-flex flex-column align-items-center">
                            <span class="text-warning fw-bold rating-stars">
                                ⭐ {{ number_format($book->average_rating, 1) }}
                            </span>
                            <small class="text-withe">
                                ({{ $book->ratings_count }} reviews)
                            </small>
                        </div>
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
                <tr class="book-row">
                    <td colspan="7" class="text-white py-5 fs-5">
                        <i class="bi bi-exclamation-circle d-block mb-2 fs-2"></i>
                        No books found matching your criteria.
                    </td>
                </tr>
                @endforelse
                <div class="stars-bg"></div>

                <style>
                    .stars-bg {
                        position: fixed;
                        inset: 0;
                        pointer-events: none;
                        z-index: 0;
                        overflow: hidden;
                    }

                    .star {
                        position: absolute;
                        color: #ffc107;
                        font-size: 14px;
                        animation: fall linear infinite;
                        opacity: 0.8;
                    }

                    @keyframes fall {
                        0% {
                            transform: translateY(-10vh);
                            opacity: 0;
                        }

                        10% {
                            opacity: 1;
                        }

                        100% {
                            transform: translateY(110vh);
                            opacity: 0;
                        }
                    }

                    /* === Book Row Hover Effect === */
                    .book-row {
                        transition: all 0.35s ease;
                        transform-style: preserve-3d;
                    }

                    .book-row:hover {
                        transform: translateY(-6px) scale(1.01);
                        box-shadow:
                            0 8px 30px rgba(255, 193, 7, 0.35),
                            inset 0 0 12px rgba(255, 193, 7, 0.15);
                        background: rgba(255, 193, 7, 0.05);
                    }

                    /* Highlight text on hover */
                    .book-row:hover td {
                        color: #fff;
                    }

                    /* Glow effect on rating stars */
                    .book-row:hover .text-warning {
                        text-shadow: 0 0 8px rgba(255, 193, 7, 0.9);
                    }
                    
                </style>
                <script>
                    const container = document.querySelector('.stars-bg');

                    for (let i = 0; i < 30; i++) {
                        const star = document.createElement('div');
                        star.className = 'star';
                        star.innerHTML = '⭐';
                        star.style.left = Math.random() * 100 + 'vw';
                        star.style.animationDuration = (5 + Math.random() * 5) + 's';
                        star.style.animationDelay = Math.random() * 5 + 's';
                        container.appendChild(star);
                    }
                </script>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $books->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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

    /* ===== Book Row Animation ===== */
    .book-row {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeUp 0.6s ease forwards;
    }

    /* stagger animation */
    .book-row:nth-child(1) {
        animation-delay: 0.05s;
    }

    .book-row:nth-child(2) {
        animation-delay: 0.1s;
    }

    .book-row:nth-child(3) {
        animation-delay: 0.15s;
    }

    .book-row:nth-child(4) {
        animation-delay: 0.2s;
    }

    .book-row:nth-child(5) {
        animation-delay: 0.25s;
    }

    /* hover effect */
    .book-row:hover {
        transform: translateY(-4px) scale(1.01);
        background: rgba(255, 193, 7, 0.05);
        transition: all 0.25s ease;
    }

    /* rating animation */
    .book-row:hover .rating-stars {
        transform: scale(1.15);
        transition: transform 0.25s ease;
    }

    /* keyframes */
    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .book-cover {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .book-row:hover .book-cover {
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
    }

    .book-row:hover td:nth-child(1) {
        transform: translateZ(30px);
    }

    .book-row:hover td:nth-child(2) {
        transform: translateZ(20px);
    }

    .book-row:hover td:nth-child(3) {
        transform: translateZ(10px);
    }
</style>
@endsection