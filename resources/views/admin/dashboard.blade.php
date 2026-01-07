@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card-glass p-3 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary small text-uppercase">Total Books</h6>
                        <h3 class="text-white mb-0">{{ $booksCount }}</h3>
                    </div>
                    <i class="bi bi-book text-warning fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-glass p-3 border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary small text-uppercase">Active Borrowing</h6>
                        <h3 class="text-white mb-0">{{ $activeBorrowingsCount }}</h3>
                    </div>
                    <i class="bi bi-arrow-repeat text-info fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-glass p-3 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary small text-uppercase">Total Users</h6>
                        <h3 class="text-white mb-0">{{ $usersCount }}</h3>
                    </div>
                    <i class="bi bi-people text-success fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-glass p-3 border-start border-danger border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary small text-uppercase">Categories</h6>
                        <h3 class="text-white mb-0">{{ $categoriesCount }}</h3>
                    </div>
                    <i class="bi bi-tags text-danger fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-glass p-4 h-100">
                <h5 class="text-warning mb-4"><i class="bi bi-graph-up me-2"></i> Borrowing Activity (Last 7 Days)</h5>
                <canvas id="borrowingChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-glass p-4 h-100">
                <h5 class="text-warning mb-4"><i class="bi bi-clock-history me-2"></i> Recent Activity</h5>
                <div class="list-group list-group-flush bg-transparent">
                    @foreach($recentBorrowings as $borrow)
                    <div class="list-group-item bg-transparent border-secondary px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="text-white mb-1 small">{{ $borrow->book->title }}</h6>
                            <small class="text-warning" style="font-size: 0.7rem;">{{ $borrow->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-secondary small">Borrowed by: {{ $borrow->user->name }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('borrowingChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($borrowingsData->pluck('date')) !!},
            datasets: [{
                label: 'Books Borrowed',
                data: {!! json_encode($borrowingsData->pluck('count')) !!},
                borderColor: '#f59e0b', 
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#888' } },
                x: { grid: { display: false }, ticks: { color: '#888' } }
            }
        }
    });
</script>

<style>
    .card-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
    }
</style>
@endsection