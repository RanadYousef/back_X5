@extends('layouts.admin')

@section('content')
<div class="card-glass p-4">
    <h2 class="fw-bold text-danger mb-4">
        <i class="bi bi-trash"></i> Trashed Books
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark table-dark-custom table-hover align-middle text-center">
            <thead>
                <tr class="text-warning">
                    <th>Title</th>
                    <th>Category</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->category->name ?? '—' }}</td>
                        <td>{{ $book->deleted_at->diffForHumans() }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <form action="{{ route('books.restore', $book->id) }}" method="POST">
                              @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                 </button>
                            </form>

                            <form method="POST" action="{{ route('books.forceDelete', $book->id) }}"
                                  onsubmit="return confirm('Delete permanently?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-white py-4">
                            No trashed books
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
@endsection
