@extends('layouts.admin')

@section('content')

<style>
/* Card animation */
.review-card {
    background: linear-gradient(145deg, #1e1e2f, #2a2a40);
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,.3);
    transition: transform .3s ease, box-shadow .3s ease;
    position: relative;
    overflow: hidden;
}

.review-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 18px 40px rgba(0,0,0,.45);
}

/* Decorative overlay (IMPORTANT FIX) */
.review-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(255,193,7,.15), transparent 60%);
    pointer-events: none; /* ⭐ الحل هنا */
}

/* Make sure buttons are clickable */
.review-card button,
.review-card form {
    position: relative;
    z-index: 5;
}

/* Text colors */
.review-card h6 {
    color: #fff;
}

.review-card p {
    color: #dcdcdc;
}

.review-card small {
    color: #b5b5b5;
}
</style>

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
                        @endif">
                        {{ ucfirst($review->status) }}
                    </span>

                    <small>⭐ {{ $review->rating }}/5</small>
                </div>

                <h6 class="fw-bold">{{ $review->book->title }}</h6>

                <small class="mb-2">
                    by {{ $review->user->name ?? 'User Deleted' }}
                </small>

                <p class="mt-2">
                    {{ Str::limit($review->comment, 120) }}
                </p>

                @role('employee')
                <div class="d-flex gap-2 mt-auto pt-3">

                    @if($review->status !== 'approved')
                    <form method="POST" action="{{ route('reviews.approve', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>
                    @endif

                    @if($review->status !== 'rejected')
                    <form method="POST" action="{{ route('reviews.reject', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-warning">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </form>
                    @endif

                    <form method="POST" action="{{ route('reviews.destroy', $review) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Delete this review?')"
                                class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </div>
                @endrole

            </div>
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
