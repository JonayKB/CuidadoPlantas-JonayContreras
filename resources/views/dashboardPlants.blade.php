<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

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

        <div class="container-fluid">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                </div>
            @endif
            <div class="row flex-nowrap">
                <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-white border border-3">
                    <div
                        class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                        <a href="/"
                            class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-decoration-none">
                            <span class="fs-5 d-none d-sm-inline">Dashboard</span>
                        </a>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start"
                            id="menu">
                            <li>
                                <a href="/dashboard"
                                    class="nav-link align-middle {{ Request::is('dashboard') ? 'active' : '' }}">
                                    <i class="fs-4 bi-people"></i>
                                    <span class="ms-1 d-none d-sm-inline">Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="/dashboardPosts"
                                    class="nav-link align-middle {{ Request::is('dashboardPosts') || Request::is('postTrash') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-post fs-4"></i>
                                    <span class="ms-1 d-none d-sm-inline">Posts</span>
                                </a>
                            </li>
                            <li>
                                <a href="/dashboardCategories"
                                    class="nav-link align-middle {{ Request::is('dashboardCategories') ? 'active' : '' }}">
                                    <i class="bi bi-bookmark fs-4"></i>
                                    <span class="ms-1 d-none d-sm-inline">Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="/dashboardPlants"
                                    class="nav-link align-middle {{ Request::is('dashboardPlants') ? 'active' : '' }}">
                                    <i class="bi bi-flower2 fs-4"></i>
                                    <span class="ms-1 d-none d-sm-inline">Plants</span>
                                </a>
                            </li>
                            <li>
                                <a href="/dashboardVerification"
                                    class="nav-link align-middle {{ Request::is('dashboardVerification') ? 'active' : '' }}">
                                    <i class="bi bi-info-square fs-4"></i>
                                    <span class="ms-1 d-none d-sm-inline">Need Verification</span>
                                    @if ($userNeedsVerification > 0)
                                        <span class="top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $userNeedsVerification }}
                                            <span class="visually-hidden">needs verification</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="/dashboardReports"
                                    class="nav-link align-middle {{ Request::is('dashboardReports') ? 'active' : '' }}">
                                    <i class="bi bi-flag fs-4"></i>
                                    <span class="ms-1 d-none d-sm-inline">Reports</span>
                                    @if ($postsReporteds > 0)
                                        <span class="top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $postsReporteds }}
                                            <span class="visually-hidden">reporteds posts</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                        <hr>
                    </div>
                </div>
                <div class="col py-3 mt-5">
                    <div class="table-responsive">
                        <table class="table table-success table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plants as $plant)
                                    <tr>
                                        <td>{{ $plant->name }}</td>
                                        <td>{{ $plant->type->name }}</td>

                                        <td>
                                            @if (!$trash)
                                                <!-- Edit Button -->
                                                <a href="{{ route('plants.edit', $plant->plant_id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>

                                                <!-- Delete Button with Modal Trigger -->
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deletePostModal{{ $plant->plant_id }}">
                                                    Delete
                                                </button>
                                            @else
                                                <!-- Restore Button -->
                                                <a href="{{ route('plants.restore', $plant->plant_id) }}"
                                                    class="btn btn-sm btn-primary">Restore</a>
                                            @endif

                                            <!-- Delete Modal for Each Post -->
                                            <div class="modal fade" id="deletePostModal{{ $plant->plant_id }}"
                                                tabindex="-1"
                                                aria-labelledby="deletePostModalLabel{{ $plant->plant_id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deletePostModalLabel{{ $plant->plant_id }}">Delete
                                                                Plant</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete the plant named
                                                            <strong>{{ $plant->name }}</strong>? This action cannot be
                                                            undone.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form method="POST"
                                                                action="{{ route('plants.delete', $plant->plant_id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $plants->links('pagination::bootstrap-5') }}
                        @if (!$trash)
                            <!-- Create Button -->
                            <a href="{{ route('plants.create') }}" class="btn btn-lg btn-success">Create</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-0 end-0 m-5">
            @if (Request::is('dashboardPlants') || Request::is('plantTrash'))
                @if (!$trash)
                    <div class="btn-group">
                        <a href="{{ route('plants.trash') }}" class="btn btn-primary"><i
                                class="bi bi-trash-fill"></i></a>
                    </div>
                @else
                    <div class="btn-group">
                        <a href="{{ route('dashboardPlants') }}" class="btn btn-primary">Original</a>
                    </div>
                @endif
            @endif

        </div>




    </div>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
