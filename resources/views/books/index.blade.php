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
                        <td>
                            @if($book->cover_image)
                                <img src="{{ asset('storage/'.$book->cover_image) }}"
                                     class="rounded shadow"
                                     width="60">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        <td class="fw-semibold">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->publish_year }}</td>
                        <td>{{ $book->language }}</td>

                        <td>
                            <span class="badge bg-success fs-6">
                                {{ $book->copies_number }}
                            </span>
                        </td>

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
                        <td colspan="7" class="text-muted py-4">
                            No books available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection