@extends('layouts.admin')

@section('content')
<div class="card-glass">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h3 class="text-warning mb-0">
                <i class="bi bi-shield-check me-2"></i>Borrowing Operations
            </h3>
            <p class="text-muted small mb-0">Action required for pending requests</p>
        </div>
        
        <a href="{{ route('borrowings.history') }}" class="btn btn-outline-info btn-sm shadow-sm px-3">
            <i class="bi bi-clock-history me-2"></i>View Borrowing Logs
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger bg-danger text-white border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="mb-2">
        <h5 class="text-white mb-4 d-flex align-items-center">
            <span class="badge bg-warning text-dark me-2">{{ $pendingRequests->count() }}</span>
            Incoming Requests Queued
        </h5>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle border-secondary text-center">
                <thead>
                    <tr class="text-warning">
                        <th class="text-start" width="30%">Book & Inventory</th>
                        <th width="20%">User</th>
                        <th width="20%">Request Type</th>
                        <th width="30%">Decision</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $request)
                        <tr class="border-secondary">
                            <td class="text-start">
                                <strong class="text-white">{{ $request->book->title }}</strong><br>
                                @if($request->book->copies_number > 0)
                                    <small class="text-success small-caps">
                                        <i class="bi bi-box-seam me-1"></i>{{ $request->book->copies_number }} available
                                    </small>
                                @else
                                    <small class="text-danger small-caps">
                                        <i class="bi bi-x-octagon me-1"></i>Out of stock
                                    </small>
                                @endif
                            </td>
                            <td class="text-white-50">
                                <i class="bi bi-person-circle me-1"></i>{{ $request->user->name }}
                            </td>
                            <td>
                                <span class="badge bg-dark border {{ $request->request_type == 'borrow' ? 'border-info text-info' : 'border-warning text-warning' }} text-uppercase px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="bi {{ $request->request_type == 'borrow' ? 'bi-box-arrow-up-right' : 'bi-box-arrow-in-down' }} me-1"></i>
                                    {{ $request->request_type }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @php
                                        $canApprove = ($request->request_type === 'borrow' && $request->book->copies_number > 0) || $request->request_type === 'return';
                                    @endphp

                                    @if($canApprove)
                                        <form action="{{ route('borrowings.approve', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm px-3 shadow-sm hover-grow">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-dark btn-sm px-3 border-secondary text-muted" disabled title="No inventory to borrow">
                                            No Stock
                                        </button>
                                    @endif

                                    <form action="{{ route('borrowings.reject', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 shadow-sm hover-grow">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted italic">
                                <i class="bi bi-check2-all me-2"></i>All caught up! No requests awaiting approval.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection