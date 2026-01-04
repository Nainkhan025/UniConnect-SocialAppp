<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid align-items-center">

        <div class="row w-100 align-items-center">

            <!-- Left Section: Logo + Search -->
            <div class="ps-4 col-md-4 d-flex align-items-center">
                <!-- Logo -->
                <a class="navbar-brand fw-bold text-primary me-3" href="{{ route('posts.index') }}">
                    <img src="{{ asset('images/uni_logo.png') }}"
                         width="45" height="45"
                         alt="UniConnect Logo"
                         class="rounded-circle shadow-sm">
                </a>

                <!-- Search -->
                <form class="d-flex align-items-center position-relative flex-grow-1" style="max-width: 250px;">
                    <i class="bi bi-search position-absolute"
                       style="left: 12px; color: #888; font-size: 14px;"></i>
                    <input class="form-control form-control-sm custom-search rounded-pill ps-4"
                           type="search"
                           placeholder="  Search UniConnect..."
                           aria-label="Search">
                </form>
            </div>

            <!-- Center Section: Navigation Icons -->
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-around align-items-center w-100 nav-icons">
                    <a href="{{ route('posts.index') }}" class="nav-link px-3 fw-semibold active">
                        <i class="bi bi-house-door-fill fs-2"></i>
                    </a>
                    <a href="#" class="nav-link px-3 fw-semibold">
                        <i class="bi bi-youtube fs-2"></i>
                    </a>
                    <a href="#" class="nav-link px-3 fw-semibold">
                        <i class="bi bi-person-fill-add fs-2"></i>
                    </a>
                    <a href="#" class="nav-link px-3 fw-semibold">
                        <i class="bi bi-people-fill fs-2"></i>
                    </a>
                </div>
            </div>

            <!-- Right Section: Icons + Profile -->
            <div class="col-md-4 d-flex justify-content-end align-items-center">

                <!-- Action Icons -->
                <ul class="navbar-nav d-flex flex-row align-items-center gap-3 me-3">
                    <li class="nav-item">
                        <a href="#" class="nav-link p-0 d-flex align-items-center justify-content-center bg-light rounded-circle"
                           style="width: 40px; height: 40px;">
                            <i class="bi bi-list fs-5 text-secondary"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link p-0 d-flex align-items-center justify-content-center bg-light rounded-circle"
                           style="width: 40px; height: 40px;">
                            <i class="bi bi-chat-dots fs-5 text-secondary"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link p-0 d-flex align-items-center justify-content-center bg-light rounded-circle"
                           style="width: 40px; height: 40px;">
                            <i class="bi bi-bell fs-5 text-secondary"></i>
                        </a>
                    </li>
                </ul>

                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <a class="d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="width: 40px; height: 40px; background-color: #e9ecef;">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                 alt="Profile"
                                 class="img-fluid"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle fs-4 text-secondary"></i>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>
