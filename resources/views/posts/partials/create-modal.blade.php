<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg facebook-modal-dialog">
        <div class="modal-content border-0 shadow-lg facebook-modal-content">
            <!-- Header - Facebook Style -->
            <div class="facebook-modal-header">
                <div class="d-flex align-items-center px-4 border-bottom position-relative" style="padding-top: 20px; padding-bottom: 20px;">
                    <h5 class="modal-title mb-0 fw-bold w-100 text-center" id="createPostModalLabel" style="font-size: 20px; color: #050505; line-height: 1.2;">Create Post</h5>
                    <button type="button" class="btn-close-facebook position-absolute end-0 me-4" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body - Facebook Style -->
            <div class="modal-body facebook-modal-body p-0">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostModalForm">
                    @csrf
                    
                    <!-- Profile Section -->
                    <div class="px-4 pt-3 pb-2">
                        <div class="d-flex align-items-center">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                                     class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;" alt="Profile">
                            @else
                                <i class="bi bi-person-circle fs-2 text-secondary me-2"></i>
                            @endif
                            <strong class="text-dark">{{ Auth::user()->name }}</strong>
                        </div>
                    </div>

                    <!-- Textarea Section -->
                    <div class="px-4 pb-3">
                        <textarea name="content" id="postContentModal" class="form-control border-0 facebook-textarea" rows="4"
                                  placeholder="What's on your mind, {{ Auth::user()->name }}?" maxlength="5000"></textarea>
                        <small class="text-muted character-count-modal d-block text-end mt-1">0/5000</small>
                    </div>

                    <!-- Media Preview Section -->
                    <div id="mediaPreviewModal" class="media-preview-facebook mb-3" style="display: none;">
                        <div class="preview-container-facebook position-relative">
                            <img id="previewImageModal" class="preview-media-facebook" style="display: none;" alt="Preview">
                            <video id="previewVideoModal" class="preview-media-facebook" style="display: none;" controls></video>
                            <button type="button" class="btn-remove-media-facebook" onclick="removeMediaModal()">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="px-4 pb-3">
                        <div class="border-top border-bottom py-2">
                            <label for="mediaInputModal" class="d-flex align-items-center mb-0 w-100 upload-label-facebook" style="cursor:pointer;">
                                <div class="upload-icon-facebook me-2">
                                    <i class="bi bi-image text-primary fs-5"></i>
                                </div>
                                <span class="text-dark fw-semibold">Photo / Video</span>
                                <input type="file" id="mediaInputModal" name="media" accept="image/*,video/*" class="d-none"
                                       onchange="previewMediaModal(this)">
                            </label>
                        </div>
                    </div>

                    <!-- Post Button - Sticky at bottom -->
                    <div class="px-4 pb-3 pt-2 border-top bg-white" style="position: sticky; bottom: 0;">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold facebook-post-btn" id="submitBtnModal">
                            Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Facebook Modal Styles */
    .facebook-modal-dialog {
        max-width: 500px;
        margin: 0 auto;
    }

    .facebook-modal-content {
        border-radius: 8px;
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .facebook-modal-header {
        background: #ffffff;
        flex-shrink: 0;
    }

    .facebook-modal-header .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #050505;
        line-height: 24px;
    }

    .btn-close-facebook {
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
        padding: 0;
        flex-shrink: 0;
    }

    .btn-close-facebook:hover {
        background: #d8dadf;
        color: #050505;
    }

    .btn-close-facebook i {
        font-size: 18px;
    }

    .facebook-modal-body {
        background: #ffffff;
        max-height: calc(90vh - 60px);
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
    }

    /* Custom Scrollbar */
    .facebook-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .facebook-modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .facebook-modal-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .facebook-modal-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Textarea */
    .facebook-textarea {
        background: transparent;
        border: none;
        resize: none;
        font-size: 24px;
        line-height: 1.3333;
        color: #050505;
        padding: 0;
        min-height: 120px;
        max-height: 400px;
        overflow-y: auto;
    }

    .facebook-textarea:focus {
        outline: none;
        box-shadow: none;
        border: none;
    }

    .facebook-textarea::placeholder {
        color: #8a8d91;
        font-size: 24px;
    }

    .character-count-modal {
        font-size: 13px;
        color: #65676b;
    }

    /* Media Preview */
    .media-preview-facebook {
        margin: 0;
        padding: 0 16px;
    }

    .preview-container-facebook {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: #000;
        max-height: 400px;
        width: 100%;
    }

    .preview-media-facebook {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: contain;
        display: block;
    }

    .preview-container-facebook video {
        width: 100%;
        max-height: 400px;
    }

    .btn-remove-media-facebook {
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

    .btn-remove-media-facebook:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }

    .btn-remove-media-facebook i {
        font-size: 20px;
    }

    /* Upload Area */
    .upload-label-facebook {
        color: #050505;
        transition: color 0.2s ease;
        padding: 8px 0;
    }

    .upload-label-facebook:hover {
        color: #1877f2;
    }

    .upload-icon-facebook {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e4e6eb;
        border-radius: 50%;
    }

    .upload-label-facebook:hover .upload-icon-facebook {
        background: #d8dadf;
    }

    /* Post Button */
    .facebook-post-btn {
        background: #1877f2;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 15px;
        font-weight: 600;
        transition: background-color 0.2s ease;
        height: 36px;
    }

    .facebook-post-btn:hover {
        background: #166fe5;
    }

    .facebook-post-btn:disabled {
        background: #e4e6eb;
        color: #bcc0c4;
        cursor: not-allowed;
    }

    .facebook-post-btn.loading {
        position: relative;
        color: transparent;
    }

    .facebook-post-btn.loading::after {
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

    /* Modal Backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .facebook-modal-dialog {
            max-width: 100%;
            margin: 0;
        }

        .facebook-modal-content {
            border-radius: 0;
            max-height: 100vh;
        }

        .facebook-modal-body {
            max-height: calc(100vh - 60px);
        }

        .facebook-textarea {
            font-size: 20px;
        }

        .facebook-textarea::placeholder {
            font-size: 20px;
        }

        .preview-media-facebook {
            max-height: 300px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('createPostModal');
        const form = document.getElementById('createPostModalForm');
        const postContent = document.getElementById('postContentModal');
        const counter = document.querySelector('.character-count-modal');
        const submitBtn = document.getElementById('submitBtnModal');

        // Character counter
        if (postContent && counter) {
            postContent.addEventListener('input', function() {
                const length = this.value.length;
                const maxLength = 5000;
                counter.textContent = `${length}/${maxLength}`;
                counter.style.color = length > maxLength * 0.9 ? '#f02849' : '#65676b';
                
                // Auto-resize textarea
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 400) + 'px';
            });
        }

        // Preview media function
        window.previewMediaModal = function(input) {
            const file = input.files[0];
            if (!file) return;

            const previewContainer = document.getElementById('mediaPreviewModal');
            const previewImage = document.getElementById('previewImageModal');
            const previewVideo = document.getElementById('previewVideoModal');

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
        };

        // Remove media function
        window.removeMediaModal = function() {
            const previewContainer = document.getElementById('mediaPreviewModal');
            const previewImage = document.getElementById('previewImageModal');
            const previewVideo = document.getElementById('previewVideoModal');
            const mediaInput = document.getElementById('mediaInputModal');

            if (previewVideo.src) URL.revokeObjectURL(previewVideo.src);

            previewImage.src = '';
            previewVideo.src = '';
            previewImage.style.display = 'none';
            previewVideo.style.display = 'none';
            previewContainer.style.display = 'none';
            mediaInput.value = '';
        };

        // Form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                const content = postContent.value.trim();
                const hasMedia = document.getElementById('mediaInputModal').files.length > 0;

                if (!content && !hasMedia) {
                    e.preventDefault();
                    alert('Please add some content or media to your post.');
                    return false;
                }

                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Posting...</span>';
            });
        }

        // Reset form when modal is hidden
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                // Reset form
                if (form) {
                    form.reset();
                }
                // Clear preview
                removeMediaModal();
                // Reset counter
                if (counter) {
                    counter.textContent = '0/5000';
                    counter.style.color = '#65676b';
                }
                // Reset submit button
                if (submitBtn) {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Post';
                }
                // Reset textarea height
                if (postContent) {
                    postContent.style.height = 'auto';
                }
            });
        }

        // ESC key closes modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    });
</script>
