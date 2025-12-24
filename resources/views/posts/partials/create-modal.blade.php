<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Header -->
                <div class="modal-header border-bottom text-center">
                    <h5 class="modal-title w-100">Create Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <!-- Profile -->
                    <div class="d-flex align-items-center mb-3">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                                 class="rounded-circle me-3" width="45" height="45">
                        @else
                            <i class="bi bi-person-circle fs-2 me-3 text-secondary"></i>
                        @endif

                        <strong>{{ Auth::user()->name }}</strong>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <textarea name="content" class="form-control border-0"
                                      rows="3" placeholder="What's on your mind, {{ Auth::user()->name }}?"></textarea>
                        </div>

                        <!-- Upload Area -->
                        <div class="border rounded p-2 mb-3 d-flex align-items-center">
                            <label class="d-flex align-items-center mb-0" style="cursor:pointer;">
                                <i class="bi bi-image text-success fs-5 me-2"></i>
                                <span>Photo/Video</span>
                                <input type="file" name="media" accept="image/*,video/*" class="d-none">
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Post</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
