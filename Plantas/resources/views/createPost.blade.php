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
                    <a href="{{ route('home') }}" class="fw-semibold me-2"><i class="bi bi-arrow-90deg-left align-middle text-dark h2"></i></a>
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
            <form action="/postCreate" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="category_id" required>
                            <option selected value="any" disabled>Choose category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="plant_id" required>
                            <option selected value="any" disabled>Choose plant</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->plant_id }}">{{ $plant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="file" name="images[]" multiple>
                <button type="submit" class="btn btn-primary">Submit</button>
                <input type="hidden" name="user_id" value={{Auth::id()}}>
            </form>
        </div>


        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
