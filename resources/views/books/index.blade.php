@extends('layouts.admin')

@section('content')
<div class="card-glass p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-warning">
            <i class="bi bi-book-half"></i> Books Collection
        </h2>

        <a href="{{ route('books.create') }}" class="btn btn-3d">
            <i class="bi bi-plus-circle"></i> Add Book
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom table-hover align-middle text-center">
            <thead>
                <tr class="text-warning">
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Language</th>
                    <th>Copies</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($books as $book)
                    <tr>

                        {{-- COVER --}}
                        <td>
                            @if($book->cover_image)
                                <img src="{{ asset('storage/'.$book->cover_image) }}"
                                     class="rounded shadow"
                                     width="60">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        {{-- TITLE --}}
                        <td class="fw-semibold">{{ $book->title }}</td>

                        {{-- CATEGORY --}}
                        <td>{{ $book->category->name ?? '—' }}</td>

                        {{-- DESCRIPTION WITH TOOLTIP --}}
                        <td style="max-width: 250px;">
                            <span data-bs-toggle="tooltip" 
                                  data-bs-placement="top"
                                  title="{{ $book->description }}">
                                {{ \Illuminate\Support\Str::limit($book->description, 60) }}
                            </span>
                        </td>

                        {{-- AUTHOR --}}
                        <td>{{ $book->author }}</td>

                        {{-- YEAR --}}
                        <td>{{ $book->publish_year }}</td>

                        {{-- LANGUAGE --}}
                        <td>{{ $book->language }}</td>

                        {{-- COPIES --}}
                        <td>
                            <span class="badge bg-success fs-6">
                                {{ $book->copies_number }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('books.edit', $book) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('books.destroy', $book) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this book?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted py-4">
                            No books available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Tooltip Activation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@endsection