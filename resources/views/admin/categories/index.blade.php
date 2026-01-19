@extends('layouts.admin')

@section('content')

<style>
/* Card entrance */
.card-glass{
    animation: fadeCard .6s ease-out;
}
@keyframes fadeCard{
    from{opacity:0; transform: translateY(10px) scale(.98);}
    to{opacity:1; transform:none;}
}

/* Table rows staggered */
tbody tr{
    opacity:0;
    transform: translateY(8px);
    animation: rowFade .4s ease forwards;
}
tbody tr:nth-child(1){animation-delay:.05s;}
tbody tr:nth-child(2){animation-delay:.1s;}
tbody tr:nth-child(3){animation-delay:.15s;}
tbody tr:nth-child(4){animation-delay:.2s;}
tbody tr:nth-child(5){animation-delay:.25s;}
tbody tr:nth-child(6){animation-delay:.3s;}

@keyframes rowFade{
    to{opacity:1; transform:none;}
}

/* Buttons modern hover */
.btn{
    transition: all .25s ease, transform .2s ease;
}
.btn:hover{
    transform: translateY(-2px) scale(1.02);
}

/* Badge subtle pulse */
.badge{
    animation: pulseBadge 3s infinite ease-in-out;
}
@keyframes pulseBadge{
    0%,100%{transform:scale(1);}
    50%{transform:scale(1.05);}
}

/* Alerts smooth appear */
.alert{
    animation: alertFade .5s ease-out;
}
@keyframes alertFade{
    from{opacity:0; transform: translateY(-5px);}
    to{opacity:1; transform:none;}
}

/* Action buttons glow */
.actions .btn:hover{
    box-shadow: 0 0 10px rgba(255,193,7,.25);
}
/* Glow sweep on hover */
.btn{
    position: relative;
    overflow: hidden;
}

.btn::before{
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 120%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.15),
        transparent
    );
    transition: all .6s ease;
}

.btn:hover::before{
    left: 120%;
}

/* stronger glow for warning buttons */
.btn-outline-warning:hover,
.btn-3d:hover{
    box-shadow: 0 0 12px rgba(255,193,7,0.35),
                0 0 25px rgba(255,193,7,0.15);
}

/* subtle glow for info */
.btn-outline-info:hover{
    box-shadow: 0 0 12px rgba(13,202,240,0.35);
}

/* subtle glow for danger */
.btn-outline-danger:hover{
    box-shadow: 0 0 12px rgba(220,53,69,0.35);
}
/* Elegant soft glow for table rows */
.table tbody tr{
    transition: all .25s ease;
    position: relative;
}

.table tbody tr:hover{
    background-color: rgba(255,193,7,0.06) !important;
    box-shadow:
        inset 0 0 0 1px rgba(255,193,7,0.25),
        0 0 15px rgba(255,193,7,0.12);
    transform: translateY(-1px) scale(1.002);
}

/* Smooth highlight for cells */
.table tbody tr:hover td{
    color: #fff;
}

/* make badges glow slightly when row hovered */
.table tbody tr:hover .badge{
    box-shadow: 0 0 8px rgba(255,193,7,0.35);
}/* Premium glowing rows */
.table tbody tr{
    transition: all .3s ease;
    position: relative;
}

/* soft golden glow around row */
.table tbody tr:hover{
    background: rgba(255,193,7,0.07) !important;
    box-shadow:
        0 0 0 1px rgba(255,193,7,0.35),
        0 0 18px rgba(255,193,7,0.15),
        inset 0 0 12px rgba(255,193,7,0.05);
    transform: scale(1.004);
}

/* highlight text inside row */
.table tbody tr:hover td{
    color: #fff;
}

/* badge stronger glow */
.table tbody tr:hover .badge{
    box-shadow: 0 0 12px rgba(255,193,7,0.45);
}

/* icons subtle light */
.table tbody tr:hover i{
    filter: drop-shadow(0 0 4px rgba(255,193,7,0.6));
}.card-glass{
    backdrop-filter: blur(14px);
    background: linear-gradient(
        145deg,
        rgba(255,255,255,0.04),
        rgba(255,255,255,0.01)
    );
    border: 1px solid rgba(255,193,7,0.08);
    box-shadow: 0 0 25px rgba(0,0,0,.45);
}// Button 3D effect
h3.text-warning{
    text-shadow: 0 0 8px rgba(255,193,7,.35);
}.btn{
    backdrop-filter: blur(6px);
}

.btn-outline-warning:hover{
    background-color: rgba(255,193,7,0.08);
}.table tbody tr:hover .fw-bold{
    color:#ffc107;
    text-shadow: 0 0 6px rgba(255,193,7,.4);
}::-webkit-scrollbar{
    width:8px;
}
::-webkit-scrollbar-thumb{
    background: rgba(255,193,7,0.35);
    border-radius:10px;
}
</style>


<div class="card-glass p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <h3 class="text-warning mb-0">
            <i class="bi bi-tags me-2"></i>Categories List
        </h3>

        @can('manage categories')
            <a href="{{ route('categories.create') }}" class="btn btn-3d">
                <i class="bi bi-plus-lg me-1"></i>Add New Category
            </a>

            <a href="{{ route('categories.trash') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i> Trash Bin
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-dark text-warning border-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control bg-dark text-white border-secondary"
                           placeholder="Search category by name...">
                </div>
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-warning w-100">Search</button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </div>
    </form>

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

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle border-secondary">
            <thead>
                <tr class="text-warning">
                    <th width="10%">ID</th>
                    <th>Category Name</th>
                    <th width="20%">Books Count</th>
                    <th width="25%" class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr class="border-secondary">
                        <td class="text-secondary">#{{ $category->id }}</td><td>
                            <span class="fw-bold text-light">{{ $category->name }}</span>
                        </td>

                        <td>
                            <span class="badge bg-dark border border-warning text-warning px-3 py-2">
                                <i class="bi bi-book me-1"></i>{{ $category->books_count }} Books
                            </span>
                        </td>

                        <td>
                            <div class="actions d-flex justify-content-center gap-2">

                                <a href="{{ route('categories.show', $category->id) }}"
                                   class="btn btn-outline-info btn-sm shadow-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                @can('manage categories')
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="btn btn-outline-warning btn-sm shadow-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endcan

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection