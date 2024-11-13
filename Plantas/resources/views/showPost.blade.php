<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-dark">
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-body-secondary">
        @if (Route::has('login'))
            <div class="position-absolute top-0 end-0 p-3">
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary fw-semibold me-2">Administración</a>
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

        <!-- Post -->
        <div class="container">
            <div class="card mb-3">
                <div class="card-header">
                    {{ $post->title }}

                    <div class="float-end">
                        <span class="badge bg-primary">{{ $post->plant->name }}</span>
                        <span class="badge bg-success">{{ $post->user->name }}</span>
                        <span class="badge bg-warning">{{ $post->reports }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid">
                    <p class="card-text">{{ $post->description }}</p>
                    @if (Auth::check() && Auth::user()->id == $post->user_id)
                        <a href="{{ route('posts.edit', $post->post_id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('posts.remove', $post->post_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                    <div class="card-footer text-muted">
                        Posted on {{ $post->created_at->format('F d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            @isset($post->comments)
                <h2 class="card-title">Comments</h2>
                @foreach ($post->comments()->whereNull('parent_comment_id')->get() as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text">{{ $comment->content }}</p>
                            <div class="float-end">
                                <span class="badge bg-primary">{{ $comment->user->name }}</span>
                            </div>

                            <!-- Show replies if they exist -->
                            @foreach ($comment->replies as $reply)
                                <div class="mt-3 ps-4">
                                    <p class="card-text"><strong>Reply:</strong> {{ $reply->content }}</p>
                                    <div class="float-end">
                                        <span class="badge bg-secondary">{{ $reply->user->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endisset
        </div>

        <a href="{{ route('home') }}" class="btn btn-link">Volver</a>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
