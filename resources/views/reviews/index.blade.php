
@extends('layouts.admin')
@section('content')
<style>
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
    pointer-events: none;
}

/* Make sure buttons are clickable */
.review-card button,
.review-card form {
    position: relative;
    z-index: 5;
}
.review-card h6 { color: #fff; }
.review-card p { color: #dcdcdc; }
.review-card small { color: #b5b5b5; }

/* Flash effects */
.flash-approve { animation: flashGreen .6s ease; }
.flash-reject { animation: flashRed .6s ease; }

/* Delete break effect */
.flash-delete {
    animation: breakCard 0.6s ease forwards;
}

@keyframes flashGreen {
    0%{box-shadow:0 0 0 rgba(40,167,69,0);}
    50%{box-shadow:0 0 25px rgba(40,167,69,.85);}
    100%{box-shadow:0 0 0 rgba(40,167,69,0);}
}
@keyframes flashRed {
    0%{box-shadow:0 0 0 rgba(220,53,69,0);}
    50%{box-shadow:0 0 25px rgba(220,53,69,.85);}
    100%{box-shadow:0 0 0 rgba(220,53,69,0);}
}

/* Break card animation */
@keyframes breakCard {
    0%   { transform: rotate(0deg) translate(0,0); opacity: 1; }
    20%  { transform: rotate(-5deg) translate(-5px,5px); opacity: 1; }
    40%  { transform: rotate(5deg) translate(5px,-5px); opacity: 0.8; }
    60%  { transform: rotate(-10deg) translate(-10px,10px); opacity: 0.6; }
    80%  { transform: rotate(10deg) translate(10px,-10px); opacity: 0.4; }
    100% { transform: rotate(20deg) translate(20px,-20px); opacity: 0; }
}

.action-btn:active { transform: scale(.9); }

</style>

<div class="mb-4">
    <h2 class="fw-bold text-warning">
        <i class="bi bi-chat-square-quote"></i> Book Reviews
    </h2>
    <p class="text-white">Manage and monitor book reviews</p>
</div>

<div class="row g-4">
@forelse($reviews as $review)
    <div class="col-md-6 col-lg-4">
        <div class="card review-card h-100">
            <div class="card-body d-flex flex-column">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge @if($review->status === 'approved') bg-success
                                        @elseif($review->status === 'rejected') bg-danger
                                        @else bg-warning text-dark
                                        @endif">
                        {{ ucfirst($review->status) }}
                    </span>
                    <small>⭐️ {{ $review->rating }}/5</small>
                </div>
                <h6 class="fw-bold">{{ $review->book->title }}</h6>
                <small class="mb-2">by {{ $review->user->name ?? 'User Deleted' }}</small>
                <p class="mt-2">{{ Str::limit($review->comment, 120) }}</p>

                @role('employee')
                <div class="d-flex gap-2 mt-auto pt-3">
                    @if($review->status !== 'approved')
                        <form method="POST" action="{{ route('reviews.approve', $review) }}" class="approve-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success action-btn"><i class="bi bi-check-circle"></i></button>
                        </form>
                    @endif
                    @if($review->status !== 'rejected')
                        <form method="POST" action="{{ route('reviews.reject', $review) }}" class="reject-form">
                            @csrf

@method('PATCH')
                            <button type="submit" class="btn btn-sm btn-warning action-btn"><i class="bi bi-x-circle"></i></button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger action-btn"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @endrole
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-warning text-center">No reviews found.</div>
    </div>
@endforelse
</div>

<!-- Sounds -->
<audio id="approveSound" src="{{ asset('sounds/approve.mp3') }}"></audio>
<audio id="rejectSound" src="{{ asset('sounds/reject.mp3') }}"></audio>
<audio id="deleteSound" src="{{ asset('sounds/delete.mp3') }}"></audio>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const approveSound = document.getElementById("approveSound");
    const rejectSound  = document.getElementById("rejectSound");
    const deleteSound  = document.getElementById("deleteSound");

    // Approve
    document.querySelectorAll(".approve-form").forEach(form => {
        form.addEventListener("submit", () => {
            const card = form.closest(".review-card");
            card.classList.add("flash-approve");
            approveSound.currentTime = 0;
            approveSound.play();
        });
    });

    // Reject
    document.querySelectorAll(".reject-form").forEach(form => {
        form.addEventListener("submit", () => {
            const card = form.closest(".review-card");
            card.classList.add("flash-reject");
            rejectSound.currentTime = 0;
            rejectSound.play();
        });
    });

    // Delete with break effect
    document.querySelectorAll(".delete-form").forEach(form => {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            if(!confirm("Delete this review?")) return;

            const card = form.closest(".review-card");

            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: new FormData(form)
            })
            .then(res => {
                if(res.ok){
                    // Play delete sound
                    deleteSound.currentTime = 0;
                    deleteSound.play();

                    // Add break animation
                    card.classList.add("flash-delete");

                    // Remove card after animation ends
                    card.addEventListener("animationend", () => card.remove());
                } else {
                    alert("Delete failed");
                }
            })
            .catch(() => alert("Server error"));
        });
    });

});
</script>
@endsection