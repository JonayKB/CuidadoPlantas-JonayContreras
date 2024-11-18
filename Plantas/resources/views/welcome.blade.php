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
            <div class="fixed-top top-0 text-end end-0 z-2">
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a href="{{ url('/dashboard') }}"
                            class="btn btn-outline-secondary fw-semibold mt-3 mr-3">Dashboard</a>
                    @endif

                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a href="{{ route('posts.create') }}" class="fw-semibold"><i
                                class="bi bi-plus-circle-fill align-middle text-dark h2 mt-3 pt-3"></i></a>
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

        <!--Posts-->
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="/filter" class="text-center mb-5 mt-5 z-3" method="POST">
                @csrf
                <div class="row mb-1">
                    <div class="col-10">
                        <input type="text" class="form-control" id="searchInput" name="search"
                            placeholder="Search by title">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="category">
                            <option selected value="any">Choose category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="plant">
                            <option selected value="any">Choose plant</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->plant_id }}">{{ $plant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="plantType">
                            <option selected value="any">Choose plant type</option>
                            @foreach ($plantTypes as $plantType)
                                <option value="{{ $plantType->id }}">{{ $plantType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-12 col-md-6">
                        <a href="{{ route('posts.show', $post->post_id) }}">
                            <div class="card mb-3">
                                <div class="card-header">
                                    {{ $post->title }}
                                    <div class="float-end">
                                        <span class="badge bg-success">{{ $post->plant?->name }}</span>
                                        <span class="badge bg-dark">{{ $post->plant?->type->name }}</span>
                                        <span class="badge bg-primary">{{ $post->user?->name }}</span>
                                        <span class="badge bg-warning">{{ $post->category?->name }}</span>
                                        <span class="badge bg-danger">{{ $post->reports }}</span>
                                    </div>
                                    <div class="card-body">
                                        @if (isset($post->images[0]))
                                            <img src="{{ asset('storage/' . $post->images[0]->path) }}"
                                                alt="{{ $post->title }}" class="card-img-top img-fluid"
                                                style="object-fit: contain; height: 30rem;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="start-50 translate-middle-x mb-3 align-items-center">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>

        </div>


        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
