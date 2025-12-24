<div class="card mb-4 shadow-sm rounded-4">
    <div class="card-body p-3 d-flex align-items-center">

        @if(Auth::user()->profile_photo)
            <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                 class="rounded-circle me-3" width="55" height="55">
        @else
            <i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>
        @endif

        <div class="flex-grow-1">
            <div class="form-control rounded-pill ps-3 py-2"
                 data-bs-toggle="modal"
                 data-bs-target="#createPostModal"
                 style="cursor:pointer;background:#f5f6f7;">
                What's on your mind, {{ Auth::user()->name }}?
            </div>
        </div>

    </div>

    <hr class="my-2">

    <div class="d-flex justify-content-start pt-1 ps-3">
        <button class="btn btn-light"
                data-bs-toggle="modal"
                data-bs-target="#createPostModal">
            <i class="bi bi-image text-primary"></i> Photo/Video
        </button>
    </div>
</div>
