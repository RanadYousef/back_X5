<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>V.9 x5 | Auth</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(2,6,23,0.5), rgba(2,6,23,0.5)),
                url('{{ asset("images/auth-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e5e7eb;
        }

        /* Card */
    .auth-card {
    width: 360px;             
    max-width: 100%;
    background: rgba(15,23,42,0.92); 
    border-radius: 16px;
    padding: 20px 22px;         
    border: 1px solid rgba(245,158,11,0.35);
    transition: all .3s ease;
}



        /* Hover Glow */
        .auth-card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 0 25px rgba(245,158,11,0.35),
                0 0 60px rgba(245,158,11,0.15);
        }

        /* Logo */
        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #fbbf24;
            text-shadow:
                0 0 10px rgba(251,191,36,0.8),
                0 0 30px rgba(245,158,11,0.6);
            animation: glow 2.5s infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow:
                    0 0 8px rgba(251,191,36,0.6),
                    0 0 20px rgba(245,158,11,0.4);
            }
            to {
                text-shadow:
                    0 0 15px rgba(251,191,36,1),
                    0 0 40px rgba(245,158,11,0.9);
            }
        }

        /* Inputs */
        .form-control {
            background: rgba(2,6,23,0.8);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }

        .form-control:focus {
            background: rgba(2,6,23,0.9);
            border-color: #f59e0b;
            box-shadow: 0 0 10px rgba(245,158,11,0.4);
            color: #fff;
        }

        /* Main Button */
        .btn-main {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: #020617;
            border: none;
            font-weight: bold;
        }

        .btn-main:hover {
            box-shadow: 0 0 20px rgba(245,158,11,0.6);
            transform: scale(1.02);
        }
    </style>
</head>

<body>

<div class="auth-card text-center">

    <!-- Logo -->
    <div class="mb-4 logo">
        V.9 x5
    </div>

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
