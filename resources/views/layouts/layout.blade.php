<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body>
   //navbar
   @include('layouts.navbar')


 <!-- Main content area -->




 <div class="container-fluid mt-4 pt-4">
  <div class="row g-0">

   <!-- Left Sidebar -->
<aside class="col-md-3 d-none d-md-block bg-light shadow-sm position-fixed start-0 top-0 vh-100 mt-5 pt-4 overflow-auto">

    <div class="p-3">

        <!-- 👤 Profile Card -->
        <div class="card shadow-sm border-0 mb-4 text-center">
            <div class="card-body">

                <!-- Profile Picture -->
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                         class="rounded-circle mb-3"
                         width="120" height="120"
                         style="object-fit: cover;"
                         alt="Profile">
                @else
                    <i class="bi bi-person-circle text-secondary mb-3"
                       style="font-size: 120px;"></i>
                @endif

                <!-- Name -->
                <h6 class="fw-semibold mb-1">{{ Auth::user()->name }}</h6>

                <!-- Student Info -->
                <small class="text-muted d-block">
                    Student • UniConnect
                </small>

                <!-- Optional Profile Link -->
                <a href="#" class="btn btn-outline-primary btn-sm mt-3 w-100">
                    View Profile
                </a>
            </div>
        </div>

        <!-- 📌 Sidebar Menu (UNCHANGED) -->
        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-decoration-none sidebar-link">
                    <i class="bi bi-people fs-5 me-3 text-primary"></i> Friends
                </a>
            </li>

            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-decoration-none sidebar-link">
                    <i class="bi bi-people-fill fs-5 me-3 text-primary"></i> Groups
                </a>
            </li>

            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-decoration-none sidebar-link">
                    <i class="bi bi-collection-play fs-5 me-3 text-primary"></i> Videos
                </a>
            </li>

            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-decoration-none sidebar-link">
                    <i class="bi bi-question-circle fs-5 me-3 text-primary"></i> FAQs
                </a>
            </li>
        </ul>

    </div>
</aside>


    <!-- Main Content -->
    <main class="col-md-6 bg-light py-3 main-content"
    style="margin-left: 25%; margin-right: 25%; ">
      <div class="p-3">
        @yield('content')
      </div>
    </main>

    <!-- Right Sidebar -->
    <aside class="col-md-3 d-none d-md-block bg-light shadow-sm vh-100 right-sidebar  position-fixed end-0 top-0 vh-100 mt-5 pt-4 overflow-auto">
      <div class="p-3 position-sticky top-0">
        <h5 class="mb-4 text-primary">Suggestions</h5>
        <ul class="list-unstyled">
          <li class="mb-3 d-flex align-items-center">
            <i class="bi bi-person-circle fs-4 text-secondary me-3"></i>
            <span>John Doe</span>
          </li>
          <li class="mb-3 d-flex align-items-center">
            <i class="bi bi-person-circle fs-4 text-secondary me-3"></i>
            <span>Jane Smith</span>
          </li>
          <li class="mb-3 d-flex align-items-center">
            <i class="bi bi-person-circle fs-4 text-secondary me-3"></i>
            <span>Ali Khan</span>
          </li>
        </ul>
      </div>
    </aside>

  </div>
</div>

<!-- LIGHTBOX -->
<div id="lightbox">
    <img id="lightboxImg">
</div>





@vite(['resources/js/app.js'])
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/posts-lightbox.js') }}"></script>
    <script src="{{ asset('js/posts.js') }}"></script>





</body>
</html>
