@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card-glass p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                <h3 class="text-info mb-0"><i class="bi bi-tags-fill me-2"></i>Categories Trash</h3>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-sm">Back to Categories</a>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover border-secondary">
                    <thead>
                        <tr class="text-warning small">
                            <th>NAME</th>
                            <th>DELETED AT</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedCategories as $category)
                            <tr>
                                <td class="text-white">{{ $category->name }}</td>
                                <td class="text-secondary small">{{ $category->deleted_at->diffForHumans() }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('categories.restore', $category->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">Restore</button>
                                        </form>
                                        <form action="{{ route('categories.forceDelete', $category->id) }}" method="POST"
                                            onsubmit="return confirm('Permanent delete?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Force Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-secondary">Trash is empty.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection