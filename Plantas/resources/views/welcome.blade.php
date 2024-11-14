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
</head>

<body class="bg-light text-dark">
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-body-secondary">
        @if (Route::has('login'))
            <div class="fixed-top top-0 text-end end-0 p-3 z-3">
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary fw-semibold me-2">Dashboard</a>
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

        <!--Posts-->
        <div class="container">
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-12 col-md-6">
                        <a href="{{ route('posts.show', $post->post_id) }}">
                            <div class="card mb-3">
                                <div class="card-header">
                                    {{ $post->title }}
                                    <div class="float-end">
                                        <span class="badge bg-success">{{ $post->plant->name }}</span>
                                        <span class="badge bg-primary">{{ $post->user->name }}</span>
                                        <span class="badge bg-danger">{{ $post->reports }}</span>
                                    </div>
                                    <div class="card-body">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                            class="card-img-top img-fluid" style="object-fit: contain; height: 30rem;">
                                    </div>
                                    @if (auth()->check() && auth()->user()->name == $post->user?->name)
                                        <div class="card-footer text-muted">
                                            <a href="{{ route('posts.edit', $post->post_id) }}">Edit</a>
                                            <form action="{{ route('posts.remove', $post->post_id) }}" method="POST"
                                                class="float-end">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 align-items-center">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>

        </div>


        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
