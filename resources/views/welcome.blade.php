<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
        }

        .btn-warning-custom {
            background-color: #f59e0b;
            color: #000;
            font-weight: bold;
            border: none;
            padding: 12px 30px;
            transition: 0.3s;
        }

        .btn-warning-custom:hover {
            background-color: #fbbf24;
            transform: scale(1.05);
        }

        .brand-text {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 card-glass">
                <i class="bi bi-book-half text-warning display-1 mb-4"></i>
                <h1 class="brand-text text-warning mb-3">DIGITAL LIBRARY</h1>
                <p class="lead mb-5 text-light">Welcome to the future of library management. A seamless experience for
                    librarians and readers alike.</p>

                <div class="d-flex justify-content-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="btn btn-warning-custom rounded-pill text-uppercase">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning-custom rounded-pill text-uppercase px-5">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-light rounded-pill text-uppercase px-5">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>

</html>