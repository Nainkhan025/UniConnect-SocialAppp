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
        <div class="card shadow-sm border-0 rounded-4 create-post-card">
            <div class="card-header bg-white border-bottom text-center position-relative">
                <h5 class="mb-0 fw-semibold">Create Post</h5>
                <button type="button" class="btn-close-custom position-absolute end-0 me-3" onclick="closeCreatePost()"
                    aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if (Auth::user()->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                            class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;"
                            alt="Profile">
                    @else
                        <i class="bi bi-person-circle fs-2 text-secondary me-2"></i>
                    @endif
                    <strong>{{ Auth::user()->name }}</strong>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
                    @csrf
                    <div class="mb-3">
                        <textarea name="content" id="postContent" class="form-control border-0 fs-5 post-textarea" rows="3"
                            placeholder="What's on your mind, {{ Auth::user()->name }}?" maxlength="5000"></textarea>
                        <small class="text-muted character-count">0/5000</small>
                    </div>

                    <div id="mediaPreview" class="media-preview mb-3" style="display: none;">
                        <div class="preview-container position-relative">
                            <img id="previewImage" class="preview-media" style="display: none;" alt="Preview">
                            <video id="previewVideo" class="preview-media" style="display: none;" controls></video>
                            <button type="button" class="btn-remove-media" onclick="removeMedia()">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="border rounded p-2 mb-3 upload-area">
                        <label class="d-flex align-items-center mb-0 w-100 upload-label" style="cursor:pointer;">
                            <i class="bi bi-image text-success fs-5 me-2"></i>
                            <span class="fw-semibold">Photo / Video</span>
                            <input type="file" id="mediaInput" name="media" accept="image/*,video/*" class="d-none"
                                onchange="previewMedia(this)">
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold" id="submitBtn">
                        <i class="bi bi-send me-2"></i>Post
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.previousUrl = "{{ url()->previous() }}";

        // Character counter
        const postContent = document.getElementById('postContent');
        const counter = document.querySelector('.character-count');
        postContent.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 5000;
            counter.textContent = `${length}/${maxLength}`;
            counter.style.color = length > maxLength * 0.9 ? '#f02849' : '#65676b';
        });

        // Preview media function
        function previewMedia(input) {
            const file = input.files[0];
            if (!file) return;

            const previewContainer = document.getElementById('mediaPreview');
            const previewImage = document.getElementById('previewImage');
            const previewVideo = document.getElementById('previewVideo');

            previewImage.style.display = 'none';
            previewVideo.style.display = 'none';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                if (previewVideo.src) URL.revokeObjectURL(previewVideo.src);
                previewVideo.src = URL.createObjectURL(file);
                previewVideo.style.display = 'block';
                previewVideo.load();
            }
            previewContainer.style.display = 'block';
        }

        // Remove media
        function removeMedia() {
            const previewContainer = document.getElementById('mediaPreview');
            const previewImage = document.getElementById('previewImage');
            const previewVideo = document.getElementById('previewVideo');
            const mediaInput = document.getElementById('mediaInput');

            if (previewVideo.src) URL.revokeObjectURL(previewVideo.src);

            previewImage.src = '';
            previewVideo.src = '';
            previewImage.style.display = 'none';
            previewVideo.style.display = 'none';
            previewContainer.style.display = 'none';
            mediaInput.value = '';
        }

        // Close create post
        function closeCreatePost() {
            const content = document.getElementById('postContent').value;
            const hasMedia = document.getElementById('mediaInput').files.length > 0;

            if (content.trim() || hasMedia) {
                if (confirm('You have unsaved changes. Are you sure you want to close?')) {
                    window.location.href = window.previousUrl;
                }
            } else {
                window.location.href = window.previousUrl;
            }
        }

        // Form submission
        document.getElementById('createPostForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const content = document.getElementById('postContent').value.trim();
            const hasMedia = document.getElementById('mediaInput').files.length > 0;

            if (!content && !hasMedia) {
                e.preventDefault();
                alert('Please add some content or media to your post.');
                return false;
            }

            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Posting...</span>';
        });

        // ESC key closes post
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeCreatePost();
        });
    </script>

    <style>
        /* Create Post Card Styles */
        .create-post-card {
            max-width: 680px;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            border: 1px solid #dadde1;
        }

        .create-post-card .card-header {
            padding: 16px;
            border-bottom: 1px solid #e4e6eb;
        }

        .create-post-card .card-header h5 {
            color: #050505;
            font-size: 20px;
            font-weight: 700;
        }

        .btn-close-custom {
            background: #e4e6eb;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #65676b;
            top: 50%;
            transform: translateY(-50%);
        }

        .btn-close-custom:hover {
            background: #d8dadf;
            color: #050505;
        }

        .btn-close-custom i {
            font-size: 18px;
        }

        .post-textarea {
            background: #f0f2f5;
            border-radius: 8px;
            padding: 12px;
            resize: vertical;
            min-height: 100px;
            font-size: 15px;
            color: #050505;
            transition: background-color 0.2s ease;
        }

        .post-textarea:focus {
            background: #ffffff;
            border: 1px solid #1877f2;
            outline: none;
            box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.1);
        }

        .post-textarea::placeholder {
            color: #8a8d91;
        }

        .character-count {
            font-size: 13px;
            display: block;
            text-align: right;
            margin-top: 4px;
        }

        .upload-area {
            background: #f0f2f5;
            border: 1px dashed #dadde1 !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            background: #e4e6eb;
            border-color: #1877f2 !important;
        }

        .upload-label {
            color: #050505;
            transition: color 0.2s ease;
        }

        .upload-area:hover .upload-label {
            color: #1877f2;
        }

        .media-preview {
            margin-top: 12px;
        }

        .preview-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
            max-height: 500px;
        }

        .preview-media {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            display: block;
        }

        .preview-container video {
            width: 100%;
            max-height: 500px;
        }

        .btn-remove-media {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: white;
            z-index: 10;
        }

        .btn-remove-media:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .btn-remove-media i {
            font-size: 20px;
        }

        #submitBtn {
            background: #1877f2;
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.2s ease;
            height: 44px;
        }

        #submitBtn:hover {
            background: #166fe5;
        }

        #submitBtn:disabled {
            background: #e4e6eb;
            color: #bcc0c4;
            cursor: not-allowed;
        }

        #submitBtn.loading {
            position: relative;
            color: transparent;
        }

        #submitBtn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .create-post-card {
                margin: 10px auto;
                border-radius: 0;
            }

            .preview-media {
                max-height: 300px;
            }
        }
    </style>
