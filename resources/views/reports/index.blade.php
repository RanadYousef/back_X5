@extends('layouts.admin')

@section('content')
    <div class="card-glass p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning m-0">
                <i class="bi bi-graph-up-arrow"></i> نظام التقارير الإحصائية
            </h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <p class="text-white-50 mb-4 text-center">اختر النطاق الزمني لعرض إحصائيات التقييم، الاستعارات النشطة،
                    وتحليل أداء الكتب.</p>

                @if ($errors->any())
                    <div class="alert alert-danger bg-dark text-danger border-danger shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reports.generate') }}" method="POST"
                    class="bg-dark-custom p-4 rounded border border-warning shadow">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label text-warning fw-bold">من تاريخ:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-warning border-warning">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                                <input type="date" name="start_date" id="start_date"
                                    class="form-control bg-dark text-white border-warning" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label text-warning fw-bold">إلى تاريخ:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-warning border-warning">
                                    <i class="bi bi-calendar-check"></i>
                                </span>
                                <input type="date" name="end_date" id="end_date"
                                    class="form-control bg-dark text-white border-warning" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-3d py-3 fs-5">
                            <i class="bi bi-file-earmark-bar-graph"></i> عرض التقرير التحليلي
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-dark-custom {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-3d {
            background: linear-gradient(145deg, #ffc107, #e0a800);
            color: #000;
            font-weight: bold;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-3d:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
            color: #000;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>
@endsection