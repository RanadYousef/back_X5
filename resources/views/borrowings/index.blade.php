@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-warning">
            <i class="bi bi-arrow-left-right"></i> Borrowings Management
        </h2>
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom table-hover align-middle text-center">
            <thead>
                <tr class="text-warning">
                    <th>Book</th>
                    <th>User</th>
                    <th>Borrowed At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($borrowings as $borrowing)
                    <tr>
                        <td class="fw-semibold">
                            {{ $borrowing->book->title ?? 'Deleted Book' }}
                        </td>

                        <td>
                            {{ $borrowing->user->name ?? 'Deleted User' }}
                        </td>

                        <td>
                            {{ $borrowing->borrowed_at?->format('Y-m-d') ?? '-' }}
                        </td>

                        <td>
                            @if($borrowing->status === 'borrowed')
                                <span class="badge bg-warning text-dark">Borrowed</span>
                            @else
                                <span class="badge bg-success">Returned</span>
                            @endif
                        </td>

                        <td>
                            @if($borrowing->status === 'borrowed')
                                <form method="POST"
                                      action="{{ route('borrowings.return', $borrowing) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Return this book?')">
                                        <i class="bi bi-arrow-return-left"></i> Return
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">
                            No borrowings found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection