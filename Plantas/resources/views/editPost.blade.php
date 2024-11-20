<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Post</title>

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
            <form action="{{ route('posts.update', $post->post_id) }}" method="post">
                @csrf
                @method('PATCH')
                <input type="hidden" name="post_id" value={{ $post->post_id }}>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ e($post->title) }}"">
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="category_id">
                            <option selected value={{ $post->category->id }}>{{ $post->category->name }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="filterSelect" name="plant_id">
                            <option selected value={{ $post->plant->plant_id }}>{{ $post->plant->name }}</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->plant_id }}">{{ $plant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="10">{{ $post->description }}</textarea>
                    <div class="mt-3">
                        <small id="charCount" class="form-text text-muted d-block mb-3">Characters remaining:
                            250 - {{ strlen($post->content) }}</small>

                        <script>
                            function countCharacters() {
                                var content = document.getElementById('content');
                                var charCount = document.getElementById('charCount');
                                charCount.textContent = "Characters remaining: 250 - " + content.value.length;
                                if (content.value.length > 250) {
                                    content.value = content.value.substr(0, 250);
                                }
                            }
                        </script>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="{{ route('home') }}" class="float-end btn btn-danger">Back to Home</a>
            </form>




        </div>



        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
