@extends('layouts.admin')

@section('content')
<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h3 class="text-info mb-0">
                <i class="bi bi-clock-history me-2"></i>Borrowing Records
            </h3>
            <p class="text-muted small mb-0">View active logs and past history</p>
        </div>
        
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-warning btn-sm shadow-sm px-3">
            <i class="bi bi-arrow-left-circle me-2"></i>Back to Pending Requests
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="mb-4 text-end">
        <span class="badge bg-dark border border-info text-info px-3 py-2">
            Total Records: {{ $borrowings->count() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle border-secondary">
            <thead>
                <tr class="text-info">
                    <th width="25%">Book Title</th>
                    <th width="15%">User</th>
                    <th width="15%">Borrowed Date</th>
                    <th width="15%">Due Date</th>
                    <th width="15%">Status</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $borrowing)
                    <tr class="border-secondary">
                        <td>
                            <strong class="text-white">{{ $borrowing->book->title }}</strong>
                        </td>
                        <td class="text-white-50">
                            <i class="bi bi-person me-1"></i>{{ $borrowing->user->name }}
                        </td>
                        <td class="small text-muted">
                            {{ $borrowing->borrowed_at->format('Y-m-d') }}
                        </td>
                        <td>
                            @php
                                $isOverdue = $borrowing->due_date < now() && $borrowing->status === 'borrowed';
                            @endphp
                            <span class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-info small' }}">
                                {{ $borrowing->due_date ? $borrowing->due_date->format('Y-m-d') : '-' }}
                                @if($isOverdue) <i class="bi bi-exclamation-triangle ms-1"></i> @endif
                            </span>
                        </td>
                        <td>
                            @if($borrowing->status === 'borrowed')
                                <span class="badge bg-dark border border-warning text-warning fw-light">
                                    <i class="bi bi-book me-1"></i> In Possession
                                </span>
                            @else
                                <span class="badge bg-dark border border-success text-success fw-light">
                                    <i class="bi bi-check2-all me-1"></i> Returned
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($borrowing->status === 'borrowed')
                                @php
                                    $returnRequest = \App\Models\BorrowingRequest::where('borrowing_id', $borrowing->id)
                                        ->where('request_type', 'return')
                                        ->where('status', 'pending')
                                        ->first();
                                @endphp

                                @if($returnRequest)
                                    <form action="{{ route('admin.borrowings.approve', $returnRequest->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning shadow-sm px-3 fw-bold">
                                            Confirm Handover
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted x-small italic" style="font-size: 0.75rem;">
                                        Waiting User Request
                                    </span>
                                @endif
                            @else
                                <div class="text-muted x-small">
                                    <i class="bi bi-calendar-check me-1"></i>{{ $borrowing->returned_at->format('Y-m-d') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted italic">
                            <i class="bi bi-inbox me-2"></i>No borrowing history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection