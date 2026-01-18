@extends('layouts.admin')

@section('content')
    <div class="card-glass p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
            <div>
                <h3 class="text-info mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Borrowing Records
                </h3>
                <p class="text-white-50 small mb-0">View active logs and past history</p>
            </div>

            <a href="{{ route('borrowings.index') }}" class="btn btn-outline-warning btn-sm shadow-sm px-3 fw-bold">
                <i class="bi bi-arrow-left-circle me-2"></i>Back to Pending Requests
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-end">
            <span class="badge bg-dark border border-info text-info px-3 py-2 fs-6">
                Total Records: {{ $borrowings->count() }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle border-secondary">
                <thead>
                    <tr class="text-info border-bottom border-info">
                        <th width="25%" class="py-3">Book Title</th>
                        <th width="15%" class="py-3">User</th>
                        <th width="15%" class="py-3">Borrowed Date</th>
                        <th width="15%" class="py-3">Due Date</th>
                        <th width="15%" class="py-3">Status</th>
                        <th width="15%" class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $borrowing)
                        <tr class="border-secondary">
                            <td>
                                <strong class="text-warning">{{ $borrowing->book->title }}</strong>
                            </td>
                            <td>
                                <span class="text-white"><i
                                        class="bi bi-person me-1 text-info"></i>{{ $borrowing->user->name }}</span>
                            </td>
                            <td>
                                <span class="text-light fw-bold">
                                    <i class="bi bi-calendar3 me-1 text-info"></i>{{ $borrowing->borrowed_at->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $isOverdue = $borrowing->due_date < now() && $borrowing->status === 'borrowed';
                                @endphp
                                <span class="{{ $isOverdue ? 'badge bg-danger text-white' : 'text-info fw-bold' }} px-2 py-1">
                                    {{ $borrowing->due_date ? $borrowing->due_date->format('Y-m-d') : '-' }}
                                    @if($isOverdue) <i class="bi bi-exclamation-triangle ms-1"></i> @endif
                                </span>
                            </td>
                            <td>
                                @if($borrowing->status === 'borrowed')
                                    <span class="badge bg-transparent border border-warning text-warning px-3 py-2">
                                        <i class="bi bi-book me-1"></i> In Possession
                                    </span>
                                @else
                                    <span class="badge bg-transparent border border-success text-success px-3 py-2">
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
                                        <form action="{{ route('borrowings.approve', $returnRequest->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning shadow-sm px-3 fw-bold text-dark">
                                                Confirm Handover
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-white-50 small italic">
                                            Waiting User Request
                                        </span>
                                    @endif
                                @else
                                    <div class="text-success fw-bold small">
                                        <i class="bi bi-calendar-check me-1"></i>{{ $borrowing->returned_at->format('Y-m-d') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-white-50 italic fs-5">
                                <i class="bi bi-inbox me-2"></i>No borrowing history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection