@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold text-warning">
        <i class="bi bi-chat-square-quote"></i> Book Reviews
    </h2>
    <p class="text-muted">Manage and monitor book reviews</p>
</div>

<div class="row g-4">
@forelse($reviews as $review)
    <div class="col-md-6 col-lg-4">
       <div class="card review-card h-100">

            <div class="card-body d-flex flex-column">
           



            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge 
                    @if($review->status === 'approved') bg-success
                    @elseif($review->status === 'rejected') bg-danger
                    @else bg-warning text-dark
                    @endif
                ">
                    {{ ucfirst($review->status) }}
                </span>

                <small class="text-muted">
                    ⭐ {{ $review->rating }}/5
                </small>
            </div>

            <h6 class="fw-bold text-white">{{ $review->book->title }}</h6>

<small class="text-light">
    by {{ $review->user->name }}
</small>

<p class="mt-3 text-light">
    {{ Str::limit($review->comment, 120) }}
</p>

            {{-- Actions (Employee only) --}}
            @role('employee')
            <div class="d-flex gap-2 mt-3">

                {{-- Approve --}}
                @if($review->status !== 'approved')
                <form method="POST" action="{{ route('reviews.approve', $review) }}">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-success" title="Approve">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>
                @endif      


                {{-- Reject --}}
                @if($review->status !== 'rejected')
                <form method="POST" action="{{ route('reviews.reject', $review) }}">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-warning" title="Reject">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </form>
                @endif

                {{-- Delete --}}
                <form method="POST" action="{{ route('reviews.destroy', $review) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </div>
            @endrole

        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-warning text-center">
            No reviews found.
        </div>
    </div>
@endforelse
</div>

@endsection
