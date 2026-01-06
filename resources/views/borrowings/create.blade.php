@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-journal-plus"></i>
                Create Borrowing
            </h5>
        </div>

        <div class="card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('borrowings.store') }}">
                @csrf

                {{-- Select Book --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Book</label>
                    <select name="book_id" class="form-select" required>
                        <option value="">-- Choose a book --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                {{ $book->copies_number < 1 ? 'disabled' : '' }}>
                                {{ $book->title }}
                                ({{ $book->copies_number }} copies available)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Borrowing Details Preview --}}
                <div class="border rounded p-3 mb-3 bg-light">
                    <h6 class="fw-bold mb-2 text-secondary">
                        Borrowing Details (Auto Generated)
                    </h6>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Borrowed By:</strong>
                            {{ auth()->user()->name }}
                        </li>
                        <li>
                            <strong>Borrow Date:</strong>
                            {{ now()->format('Y-m-d H:i') }}
                        </li>
                        <li>
                            <strong>Status:</strong>
                            <span class="badge bg-warning text-dark">
                                Borrowed
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    This operation will borrow <strong>one copy</strong> of the selected book.
                    The system will automatically record the user, date, and status.
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('borrowings.index') }}"
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirm Borrowing
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection