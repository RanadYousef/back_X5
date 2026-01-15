@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800 fw-bold"><i class="bi bi-file-earmark-bar-graph"></i> التقرير للمكتبة</h2>
        <button onclick="window.print()" class="btn btn-dark shadow-sm">
            <i class="bi bi-printer"></i> طباعة التقرير
        </button>
    <a href="{{ route('admin.reports.download', request()->all()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Download PDF
    </a>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">متوسط التقييم</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgRating, 1) }} / 5</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-star-fill fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">متوسط الاستعارات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgBorrowingCount, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">الكتب المتاحة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooksCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark text-white">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-clock-history"></i> استعارات نشطة (لم تُرجع بعد)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>المستعير</th>
                            <th>الكتاب</th>
                            <th>تاريخ الاستعارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBorrowings as $active)
                        <tr>
                            <td><span class="badge bg-light text-dark shadow-sm">{{ $active->user->name }}</span></td>
                            <td><strong>{{ $active->book->title }}</strong></td>
                            <td><i class="bi bi-calendar3"></i> {{ $active->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="bi bi-trophy"></i> الكتب الأعلى تقييماً</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($topRatedBooks as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $book->title }}
                            <div>
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= round($book->overall_rating) ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="bi bi-fire"></i> الكتب الأكثر استعارة</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($mostBorrowedBooks as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $book->title }}
                            <span class="badge bg-primary rounded-pill">{{ $book->borrowings_count }} مرة</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="bi bi-people"></i> الزبائن الأكثر استعارة</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($topCustomers as $customer)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $customer->name }}
                            <span class="badge bg-success rounded-pill">{{ $customer->borrowings_count }} عملية</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary px-5 shadow-sm">
            <i class="bi bi-arrow-left"></i> عودة للتقارير
        </a>
    </div>
</div>

<style>
    .bi-star-fill.text-warning { color: #FFD700 !important; }
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    @media print { .btn, .navbar, .sidebar { display: none !important; } }
</style>
@endsection