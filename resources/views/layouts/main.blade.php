<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- META --}}
        @yield('meta')

        {{-- TITLE --}}
        <title>@yield('title') | Qraved CMS</title>

        <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon"/>
        <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/qraved-styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />

        {{-- CSS --}}
        @yield('css')

    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light shadow-sm">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/img/logo.png') }}" width="130" alt="Qraved Logo">
                <span id="cms-text" class="align-bottom"
                      style="font-weight: 800; color: #343a40; font-style: italic;"
                >
                    CMS
                </span>
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

            <!-- Navbar-->
            <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">

                            Logout

                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light-red" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav" style="overflow-x: hidden;">
                            <div class="row p-3">
                                <div class="col-auto pr-1">
                                    <img class="img-thumbnail rounded-circle shadow-sm" width="50"
                                         src="https://ui-avatars.com/api/?name={{ Str::slug(auth()->user()->name) }}"
                                         alt="Profile Picture">
                                </div>
                                <div class="col pt-1">
                                    <span class="d-block small">
                                        Hello,
                                    </span>
                                    <h5>{{ Str::limit(auth()->user()->name, 10) }}</h5>
                                </div>
                            </div>
                            <a class="nav-link" href="{{ route('cms.dashboard.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                Users
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseUsers" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('cms.user.cms.index') }}">CMS</a>
                                    <a class="nav-link" href="{{ route('cms.user.qraved.index') }}">Qraved</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="{{ route('cms.restaurant.index') }}">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                Restaurants
                            </a>
                            <a class="nav-link" href="{{ route('cms.qr-code.index') }}">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                QR Codes
                            </a>
                            <a class="nav-link" href="{{ route('cms.quiz.index') }}">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-question"></i>
                                </div>
                                Quiz
                            </a>
                            <a class="nav-link" href="{{ route('cms.setting.index') }}">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                Settings
                            </a>
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                Logs
                            </a>
                        </div>
                    </div>
                    {{-- <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        {{ auth()->user()->name }}
                    </div> --}}
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h3 class="mt-4">@yield('title_content')</h3>
                        <hr>
                        @yield('content')
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Qraved CMS {{ now()->year }}</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        {{-- MODAL --}}
        @yield('modal')

        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            $(document).ready(() => {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        </script>

        @yield('js')
    </body>
</html>
