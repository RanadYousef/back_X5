@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> نظام التقارير الإحصائية</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="text-secondary mb-4">اختر النطاق الزمني لعرض إحصائيات التقييم، الاستعارات النشطة، وتحليل أداء الكتب.</p>

                    <form action="{{ route('admin.reports.generate') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label font-weight-bold">من تاريخ:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label font-weight-bold">إلى تاريخ:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="form-label font-weight-bold">تصنيف الكتب:</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- كل التصنيفات --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark btn-lg shadow-sm w-100">
                                <i class="bi bi-file-earmark-bar-graph"></i> عرض التقرير التحليلي
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection