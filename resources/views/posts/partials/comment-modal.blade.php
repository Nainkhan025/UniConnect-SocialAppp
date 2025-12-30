{{-- Copy this HTML directly into your post view --}}

{{-- Comment Modal --}}
<div class="comment-modal" id="commentModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="likes-section-header" id="likesSection">
                    <div class="likes-icon">👍</div>
                    <a href="#" class="likes-link" id="likesLink">0</a>
                </div>
            </div>
            <h5 class="modal-title">Comments</h5>
            <div class="modal-header-right">
                <button class="close-modal" type="button">&times;</button>
            </div>
        </div>

        <div class="modal-body">
            {{-- Comments Container --}}
            <div class="comments-container" id="commentsContainer">
                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner"></div>
                </div>
            </div>

            {{-- Comment Input --}}
            <div class="comment-input-container">
                <div class="user-avatar">
                    @if (auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            alt="{{ auth()->user()->name }}" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="input-wrapper">
                    <textarea id="commentInput" placeholder="Write a comment..." rows="1"></textarea>
                    <button id="submitComment" class="submit-btn" disabled>Post</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Likers Modal --}}
<div class="likers-modal" id="likersModal">
    <div class="modal-overlay"></div>
    <div class="modal-content likers-content">
        <div class="modal-header">
            <h5 class="modal-title">People who liked this</h5>
            <button class="close-modal" type="button">&times;</button>
        </div>
        <div class="modal-body">
            <div class="likers-list" id="likersList">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
</div>
