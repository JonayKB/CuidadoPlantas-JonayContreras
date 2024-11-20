<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Plant</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light text-dark">
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-body-secondary">
        @if (Route::has('login'))
            <div class="fixed-top top-0 text-end end-0 z-2">
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary fw-semibold mt-3 mr-3">Home</a>
                    @endif

                    <!-- Dropdown para el usuario autenticado -->
                    <div class="dropdown d-inline">
                        <button class="btn btn-outline-secondary dropdown-toggle fw-semibold mt-3 mx-3" type="button"
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu mx-5" aria-labelledby="userDropdown">
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
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary fw-semibold mt-3">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="btn btn-outline-secondary fw-semibold mt-3 mx-3">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="container">
            <form action="{{ route('plants.update', $plant->plant_id) }}" method="post">
                @csrf
                @method('PATCH')
                <input type="hidden" name="plant_id" value={{ $plant->plant_id }}>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ e($plant->name) }}">
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="type">
                            <option selected value={{ $plant->type->id }}>{{ $plant->type->name }}</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Plant</button>
                <a href="{{ route('dashboardPlants') }}" class="float-end btn btn-danger">Back to Home</a>
            </form>




        </div>



        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
