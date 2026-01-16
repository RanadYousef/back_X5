@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h2 class="h3 mb-0 text-white fw-bold">
                <i class="bi bi-file-earmark-bar-graph"></i> التقرير التحليلي للمكتبة
            </h2>
            <div class="gap-2 d-flex">
                <button onclick="window.print()" class="btn btn-light shadow-sm">
                    <i class="bi bi-printer"></i> طباعة التقرير
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-warning shadow-sm">
                    <i class="bi bi-arrow-left"></i> عودة
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 bg-dark text-white">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">متوسط التقييم العام
                                </div>
                                <div class="h5 mb-0 font-weight-bold">{{ number_format($avgRating, 1) }} / 5</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-star-fill fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 bg-dark text-white">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">معدل الاستعارة لكل
                                    كتاب</div>
                                <div class="h5 mb-0 font-weight-bold">{{ number_format($avgBorrowingCount, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-book fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 bg-dark text-white">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">الكتب المتاحة حالياً
                                </div>
                                <div class="h5 mb-0 font-weight-bold">{{ count($availableBooks) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4 bg-dark text-white border-secondary">
            <div class="card-header py-3 bg-secondary text-white">
                <h6 class="m-0 font-weight-bold"><i class="bi bi-clock-history"></i> استعارات نشطة (لم تُرجع بعد)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>المستعير</th>
                                <th>الكتاب</th>
                                <th>تاريخ الاستعارة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeBorrowings as $active)
                                <tr>
                                    <td>{{ $active->user->name }}</td>
                                    <td><strong>{{ $active->book->title }}</strong></td>
                                    <td>{{ $active->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد استعارات نشطة حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100 bg-dark text-white border-warning">
                    <div class="card-header py-3 bg-warning text-dark">
                        <h6 class="m-0 font-weight-bold"><i class="bi bi-trophy"></i> الأعلى تقييماً</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($topRatedBooks as $book)
                                <li
                                    class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center px-0 border-secondary">
                                    {{ $book->title }}
                                    <span class="text-warning small">
                                        {{ number_format($book->reviews_avg_rating, 1) }} <i class="bi bi-star-fill"></i>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100 bg-dark text-white border-primary">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold"><i class="bi bi-fire"></i> الأكثر استعارة</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($mostBorrowed as $book)
                                <li
                                    class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center px-0 border-secondary">
                                    {{ $book->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $book->borrows_count }} مرة</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100 bg-dark text-white border-success">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold"><i class="bi bi-people"></i> أفضل المستعيرين</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($topCustomers as $customer)
                                <li
                                    class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center px-0 border-secondary">
                                    {{ $customer->name }}
                                    <span class="badge bg-success rounded-pill">{{ $customer->borrowings_count }} عملية</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        @media print {
            body {
                background: white !important;
                color: black !important;
            }

            .no-print,
            .btn,
            .navbar,
            .sidebar {
                display: none !important;
            }

            .card {
                border: 1px solid #ccc !important;
                background: white !important;
                color: black !important;
            }

            .bg-dark {
                background: white !important;
                color: black !important;
            }

            .text-white {
                color: black !important;
            }

            .list-group-item {
                background: white !important;
                color: black !important;
                border-bottom: 1px solid #eee !important;
            }
        }
    </style>
@endsection