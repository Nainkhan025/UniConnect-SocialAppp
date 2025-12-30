document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) {
        console.error('CSRF token not found');
        return;
    }

    let currentPostId = null;
    let currentLikes = [];
    let isLiked = false;

    // Like Button Click
    document.addEventListener('click', async function (e) {
        if (e.target.closest('.like-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.like-btn');
            const postId = btn.dataset.postId;
            await handleLike(postId, btn);
        }

        // Comment Button Click
        if (e.target.closest('.comment-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.comment-btn');
            const postId = btn.dataset.postId;
            openCommentModal(postId);
        }

        // Submit Comment
        if (e.target.id === 'submitComment') {
            e.preventDefault();
            await submitComment();
        }

        // Close Modal - Check which modal to close
        if (e.target.classList.contains('close-modal') || e.target.closest('.close-modal')) {
            const closeBtn = e.target.closest('.close-modal') || e.target;
            const modal = closeBtn.closest('.comment-modal, .likers-modal');
            if (modal) {
                if (modal.classList.contains('comment-modal')) {
                    closeCommentModal();
                } else if (modal.classList.contains('likers-modal')) {
                    closeLikersModal();
                }
            } else {
                closeModals();
            }
        }

        // Delete Comment
        if (e.target.classList.contains('delete-comment')) {
            e.preventDefault();
            const commentId = e.target.dataset.id;
            await deleteComment(commentId);
        }

        // Show Likers
        if (e.target.closest('.likes-link') || e.target.closest('.likes-count')) {
            e.preventDefault();
            showLikers();
        }

        // Close modal on overlay click - close only the specific modal
        if (e.target.classList.contains('modal-overlay')) {
            const modal = e.target.closest('.comment-modal, .likers-modal');
            if (modal) {
                if (modal.classList.contains('comment-modal')) {
                    closeCommentModal();
                } else if (modal.classList.contains('likers-modal')) {
                    closeLikersModal();
                }
            }
        }
    });

    // Handle Like
    async function handleLike(postId, btn) {
        try {
            const response = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to like post');

            const data = await response.json();

            // Update button state
            if (data.liked) {
                btn.classList.add('liked');
                btn.querySelector('.action-text').textContent = 'Liked';
            } else {
                btn.classList.remove('liked');
                btn.querySelector('.action-text').textContent = 'Like';
            }

            // Update likes count in footer
            updateLikesCount(postId, data.count);

            // Update likes in modal if open
            if (currentPostId === postId) {
                currentLikes = data.likes;
                isLiked = data.liked;
                updateLikesSection();
            }
        } catch (error) {
            console.error('Error liking post:', error);
            alert('Failed to like post. Please try again.');
        }
    }

    // Update Likes Count in Footer
    function updateLikesCount(postId, count) {
        const statContainer = document.getElementById(`likesStat-${postId}`);
        if (!statContainer) return;

        if (count > 0) {
            if (!statContainer.querySelector('.likes-count')) {
                statContainer.innerHTML = `
                    <span class="likes-count" data-post-id="${postId}">
                        <span class="likes-icon-small">👍</span>
                        <span class="count-text">${count}</span>
                    </span>
                `;
            } else {
                statContainer.querySelector('.count-text').textContent = count;
            }
        } else {
            statContainer.innerHTML = '';
        }
    }

    // Open Comment Modal
    async function openCommentModal(postId) {
        currentPostId = postId;
        const modal = document.getElementById('commentModal');
        modal.classList.add('active');

        // Reset input
        const commentInput = document.getElementById('commentInput');
        commentInput.value = '';
        updateSubmitButton();

        // Load comments and likes
        await loadCommentsAndLikes(postId);
    }

    // Load Comments and Likes
    async function loadCommentsAndLikes(postId) {
        const container = document.getElementById('commentsContainer');
        container.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';

        try {
            const response = await fetch(`/posts/${postId}/comments`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load comments');

            const data = await response.json();

            currentLikes = data.likes || [];
            isLiked = data.is_liked || false;

            // Update likes section
            updateLikesSection();

            // Reverse comments so newest appears at bottom
            const reversedComments = [...data.comments].reverse();

            // Render comments
            renderComments(reversedComments);

            // Scroll to bottom to show newest comment
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        } catch (error) {
            console.error('Error loading comments:', error);
            container.innerHTML = '<div class="empty-state">Failed to load comments. Please try again.</div>';
        }
    }

    // Update Likes Section
    function updateLikesSection() {
        const likesLink = document.getElementById('likesLink');
        const likesSection = document.getElementById('likesSection');

        if (currentLikes.length > 0) {
            likesLink.textContent = `${currentLikes.length} ${currentLikes.length === 1 ? 'like' : 'likes'}`;
            likesSection.style.display = 'flex';
        } else {
            likesSection.style.display = 'none';
        }
    }

    // Render Comments
    function renderComments(comments) {
        const container = document.getElementById('commentsContainer');

        if (comments.length === 0) {
            container.innerHTML = '<div class="empty-state">No comments yet. Be the first to comment!</div>';
            return;
        }

        const authUserId = window.authUserId || null;

        container.innerHTML = comments.map(comment => {
            const avatar = comment.user_avatar
                ? `<img src="${comment.user_avatar}" alt="${comment.user_name}" class="comment-avatar">`
                : `<div class="avatar-placeholder"><i class="fas fa-user"></i></div>`;

            const deleteBtn = (authUserId && comment.user_id == authUserId)
                ? `<button class="comment-action-btn delete-comment" data-id="${comment.id}">Delete</button>`
                : '';

            return `
                <div class="comment-item">
                    ${avatar}
                    <div class="comment-content">
                        <div class="comment-header">
                            <a href="#" class="comment-author">${escapeHtml(comment.user_name)}</a>
                            <span class="comment-time">${comment.created_at}</span>
                        </div>
                        <p class="comment-text">${escapeHtml(comment.content)}</p>
                        ${deleteBtn ? `<div class="comment-actions">${deleteBtn}</div>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    // Submit Comment
    async function submitComment() {
        const input = document.getElementById('commentInput');
        const content = input.value.trim();

        if (!content || !currentPostId) return;

        const submitBtn = document.getElementById('submitComment');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Posting...';

        try {
            const response = await fetch(`/posts/${currentPostId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content: content })
            });

            if (!response.ok) throw new Error('Failed to post comment');

            const data = await response.json();

            // Clear input
            input.value = '';
            updateSubmitButton();

            // Reload comments
            await loadCommentsAndLikes(currentPostId);

            // Scroll to bottom to show the new comment
            const container = document.getElementById('commentsContainer');
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 150);

            // Update comment count in footer
            updateCommentCount(currentPostId);
        } catch (error) {
            console.error('Error posting comment:', error);
            alert('Failed to post comment. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Post';
        }
    }

    // Delete Comment
    async function deleteComment(commentId) {
        if (!confirm('Are you sure you want to delete this comment?')) return;

        try {
            const response = await fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to delete comment');

            // Reload comments
            await loadCommentsAndLikes(currentPostId);

            // Update comment count in footer
            updateCommentCount(currentPostId);
        } catch (error) {
            console.error('Error deleting comment:', error);
            alert('Failed to delete comment. Please try again.');
        }
    }

    // Update Comment Count in Footer
    async function updateCommentCount(postId) {
        try {
            const response = await fetch(`/posts/${postId}/comments`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            const statContainer = document.getElementById(`commentsStat-${postId}`);

            if (statContainer) {
                const count = data.comments.length;
                if (count > 0) {
                    statContainer.innerHTML = `
                        <span class="comments-count">${count} ${count === 1 ? 'comment' : 'comments'}</span>
                    `;
                } else {
                    statContainer.innerHTML = '';
                }
            }
        } catch (error) {
            console.error('Error updating comment count:', error);
        }
    }

    // Show Likers
    async function showLikers() {
        if (!currentPostId) return;

        const modal = document.getElementById('likersModal');
        const list = document.getElementById('likersList');

        modal.classList.add('active');
        list.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';

        try {
            const response = await fetch(`/posts/${currentPostId}/likers`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load likers');

            const data = await response.json();

            if (data.likes.length === 0) {
                list.innerHTML = '<div class="empty-state">No likes yet</div>';
                return;
            }

            list.innerHTML = data.likes.map(like => {
                const avatar = like.user_avatar
                    ? `<img src="${like.user_avatar}" alt="${like.user_name}" class="liker-avatar">`
                    : `<div class="avatar-placeholder"><i class="fas fa-user"></i></div>`;

                return `
                    <a href="#" class="liker-item">
                        ${avatar}
                        <span class="liker-name">${escapeHtml(like.user_name)}</span>
                    </a>
                `;
            }).join('');
        } catch (error) {
            console.error('Error loading likers:', error);
            list.innerHTML = '<div class="empty-state">Failed to load likers. Please try again.</div>';
        }
    }

    // Close Comment Modal
    function closeCommentModal() {
        document.getElementById('commentModal').classList.remove('active');
        currentPostId = null;
    }

    // Close Likers Modal
    function closeLikersModal() {
        document.getElementById('likersModal').classList.remove('active');
    }

    // Close All Modals
    function closeModals() {
        closeCommentModal();
        closeLikersModal();
    }

    // Update Submit Button State
    function updateSubmitButton() {
        const input = document.getElementById('commentInput');
        const submitBtn = document.getElementById('submitComment');

        if (input && submitBtn) {
            submitBtn.disabled = !input.value.trim();
        }
    }

    // Auto-resize textarea
    const commentInput = document.getElementById('commentInput');
    if (commentInput) {
        commentInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            updateSubmitButton();
        });

        commentInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) {
                    submitComment();
                }
            }
        });
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close on Escape key - close the topmost modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const likersModal = document.getElementById('likersModal');
            const commentModal = document.getElementById('commentModal');

            // If likers modal is open, close it first
            if (likersModal.classList.contains('active')) {
                closeLikersModal();
            }
            // Otherwise close comment modal
            else if (commentModal.classList.contains('active')) {
                closeCommentModal();
            }
        }
    });
});

