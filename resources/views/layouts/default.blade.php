<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Docsave</a>
            <div class="ml-auto pr-4 text-light"><small>Made with ♥ by <a href="https://github.com/dafroberts" target="_blank" target="_blank" class="text-light font-weight-bold">Dave</a></small></div>
        </nav>

        <div class="container-fluid">
            <div class="row main-row">
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky pt-3 position-fixed">
                        <h5 class="mt-5">Review</h5>
                        <ul class="nav flex-column">

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('dashboard') }}">
                                    <div class="icon">
                                        <i class="ri-dashboard-line"></i>
                                    </div>
                                    <div class="label">
                                        Dashboard
                                    </div>
                                </a>
                            </li>

                            {{-- <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <div class="icon">
                                        <i class="ri-file-copy-2-line"></i>
                                    </div>
                                    <div class="label">
                                        All documents @if(count($_sorted_files))<span class="badge badge-info text-light">{{ count($_sorted_files) }}</span>@endif
                                    </div>
                                </a>
                            </li> --}}

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('docs.search') }}">
                                    <div class="icon">
                                        <i class="ri-search-line"></i>
                                    </div>
                                    <div class="label">
                                        Search
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <h5 class="mt-3">Management</h5>
                        <ul class="nav flex-column">

                            

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('docs.unsorted') }}">
                                    <div class="icon">
                                        <i class="ri-file-warning-line"></i>
                                    </div>
                                    <div class="label">
                                        Unsorted documents @if(count($_unsorted_files))<span class="badge badge-danger text-light">{{ count($_unsorted_files) }}</span>@endif
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link active" href="#">
                                    <div class="icon">
                                        <i class="ri-bookmark-line"></i>
                                    </div>
                                    <div class="label">
                                        Tags <small>Coming soon</small>
                                    </div>
                                </span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('docs.backups') }}">
                                    <div class="icon">
                                        <i class="ri-database-2-line"></i>
                                    </div>
                                    <div class="label">
                                        Backups
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-5 py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @routes
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>