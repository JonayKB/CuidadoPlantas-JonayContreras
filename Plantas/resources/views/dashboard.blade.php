<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main Page</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light text-dark">
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-body-secondary">
        @if (Route::has('login'))
            <div class="fixed-top top-0 text-end end-0 p-3 z-3">
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary fw-semibold me-2">Dashboard</a>
                    @endif

                    @if (auth()->user()->roles->contains('name', 'user'))
                    <a href="{{ route('posts.create') }}" class="fw-semibold me-2"><i class="bi bi-plus-circle-fill align-middle text-dark h2"></i></a>
                @endif
                    <!-- Dropdown para el usuario autenticado -->
                    <div class="dropdown d-inline">
                        <button class="btn btn-outline-secondary dropdown-toggle fw-semibold" type="button"
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary fw-semibold me-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary fw-semibold">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="container">


        </div>


        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
