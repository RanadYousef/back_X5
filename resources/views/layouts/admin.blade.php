<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(
                rgba(15,23,42,0.85),
                rgba(15,23,42,0.85)
            ),
            url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f');
            background-size: cover;
            background-attachment: fixed;
            color: #e5e7eb;
            min-height: 100vh;
        }

        .navbar-custom {
            background: #020617;
            border-bottom: 2px solid #f59e0b;
        }

        .brand-box {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: #020617;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: bold;
        }

        .card-glass {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 20px;
        }

        .btn-3d {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: #020617;
            border: none;
        }



        
    .review-card {
        background: linear-gradient(135deg, #facc15, #fde047);
        color: #1f2937;
        border-radius: 18px;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: transform .2s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
    }

    .review-card .badge {
        font-size: 0.75rem;
    }

    .review-card .text-muted {
        color: #374151 !important;
    }


    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-4">
    <span class="brand-box">📚 LIBRARY</span>

    <div class="ms-auto d-flex gap-3">
  @role('admin')
    <a href="{{ route('users.index') }}" class="btn btn-outline-warning btn-sm">
        <i class="bi bi-people-fill"></i> Users
    </a>
@endrole

        
         <a href="{{ route('reviews.index') }}" class="btn btn-outline-warning btn-sm">reviews </a>
        <a href="{{ route('books.index') }}" class="btn btn-outline-warning btn-sm">Books</a>
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-warning btn-sm">Borrowings</a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-warning btn-sm">Categories</a>
        @role('admin')
        <a href="{{ route('roles.index') }}" class="btn btn-outline-warning btn-sm">Roles</a>
        @endrole
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container py-5">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
