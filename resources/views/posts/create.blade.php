@extends('layouts.layout')

@section('content')
<div class="container mt-4 w-75">

    <!-- ✅ Session Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 🪶 Create Post Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <!-- Header -->
        <div class="card-header bg-white border-bottom text-center position-relative">
            <h5 class="mb-0 fw-semibold">Create Post</h5>

            <a href="{{ url()->previous() }}"
               class="btn-close position-absolute end-0 me-3"></a>
        </div>

        <!-- Body -->
        <div class="card-body">

            <!-- User Info -->
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-person-circle fs-2 text-secondary me-2"></i>
                <strong>{{ Auth::user()->name }}</strong>
            </div>

            <!-- Form -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Post Text -->
                <div class="mb-3">
                    <textarea name="content"
                              class="form-control border-0 fs-5"
                              rows="3"
                              placeholder="What's on your mind, {{ Auth::user()->name }}?"
                              ></textarea>
                </div>

                <!-- Upload Area -->
                <div class="border rounded p-2 mb-3 d-flex align-items-center">
                    <label class="d-flex align-items-center mb-0 w-100" style="cursor:pointer;">
                        <i class="bi bi-image text-success fs-5 me-2"></i>
                        <span class="fw-semibold">Photo / Video</span>
                        <input type="file"
                               name="media"
                               accept="image/*,video/*"
                               class="d-none">
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    Post
                </button>
            </form>

        </div>
    </div>

</div>
@endsection
