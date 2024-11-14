<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Post of {{ $post->user->name }}</title>

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

        <!-- Post -->
        <div class="container">
            <div class="card mb-3">
                <div class="card-header">
                    {{ $post->title }}

                    <div class="float-end">
                        <span class="badge bg-success">{{ $post->plant->name }}</span>
                        <span class="badge bg-dark">{{ $post->plant->type->name }}</span>
                        <span class="badge bg-primary">{{ $post->user->name }}</span>
                        <span class="badge bg-warning">{{ $post->category->name }}</span>
                        <span class="badge bg-danger">{{ $post->reports }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($post->images as $image)
                            <div class="col" style="cursor: pointer">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->path }}"
                                    class="card-img-top img-fluid" style="object-fit: contain; max-height:10rem"
                                    data-bs-toggle="modal" data-bs-target="#modal{{ $image->id }}">
                            </div>

                            <div class="modal fade" id="modal{{ $image->id }}" tabindex="-1"
                                aria-labelledby="modalLabel{{ $image->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $image->id }}">Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                alt="Image {{ $image->id }}" class="img-fluid"
                                                style="max-width: 100%; max-height: 80vh; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="card-text text-muted">{{ $post->description }}</p>
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
                <h2 class="card-title d-flex">Comments</h2>
                @foreach ($post->comments()->whereNull('parent_comment_id')->get() as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <span class="badge bg-primary">{{ $comment->user->name }}</span>
                            <p class="card-text">{{ $comment->content }}</p>
                            <button class="btn btn-sm btn-secondary"
                                onclick="setParentCommentId({{ $comment->comment_id }},'{{ $comment->user->name }}')">Reply</button>

                            <!-- Replies -->
                            @foreach ($comment->replies as $reply)
                                <div class="mt-3 ps-4">
                                    <span class="badge bg-primary">{{ $reply->user->name }}</span>
                                    <p class="card-text"><strong>Reply:</strong> {{ $reply->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endisset

            <!-- Comment Form -->
            <form action="addComment/{{ $post->post_id }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label id="commentHeader" for="commentContent" class="form-label">New Comment: </label>

                    <textarea id="commentContent" name="commentContent" class="form-control" rows="2" required maxlength="250"></textarea>
                    <small id="commentLength" class="form-text text-muted d-block mb-3">Characters remaining:
                        250</small>
                    <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    <button type="button" id="replayButton" class="btn btn-secondary mb-3" onclick="unReply()"
                        hidden>Unreply</button>
                    <input type="hidden" id="postId" name="postId" value="{{ $post->post_id }}">
                    <input type="hidden" id="userId" name="userId" value="{{ Auth::id() }}">
                    <input type="hidden" id="parentCommentId" name="parentCommentId" value="">
                </div>

            </form>

        </div>

    </div>
    <div class="fixed-bottom mx-5 my-3" style="width: 100px">
        <a href="{{ route('home') }}" class="btn btn-danger">Back</a>
    </div>
    <script>
        function setParentCommentId(commentId, owner) {
            document.getElementById('parentCommentId').value = commentId;
            document.getElementById('commentHeader').textContent = 'Replying ' + owner + ":";
            document.getElementById('replayButton').hidden = false;
        }

        function unReply() {
            document.getElementById('parentCommentId').value = "";
            document.getElementById('commentHeader').textContent = 'New Comment: ';
            document.getElementById('replayButton').hidden = true;
        }
        document.getElementById('commentContent').addEventListener('input', function() {
            var maxLength = 250;
            var remainingLength = maxLength - this.value.length;
            document.getElementById('commentLength').textContent = "Characters remaining: " + remainingLength;
        });
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
