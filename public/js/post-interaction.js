document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found!');
        return;
    }

    // Store loaded comments count and scroll positions per post
    const postData = {};

    // Helper function to show toast
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast').forEach(toast => toast.remove());

        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 10);

        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize post data for all posts
    document.querySelectorAll('.post-footer').forEach(post => {
        const postId = post.getAttribute('data-post-id');
        const commentsList = post.querySelector('.comments-list');

        postData[postId] = {
            loadedComments: commentsList ? commentsList.children.length : 0,
            scrollPosition: 0,
            isLoading: false
        };
    });

    // ========== LIKE FUNCTIONALITY ==========
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const postId = this.getAttribute('data-post-id');
            const likeBtn = this;

            if (!postId) {
                showToast('Error: Post not found', 'error');
                return;
            }

            // Disable button during request
            likeBtn.disabled = true;
            const originalHTML = likeBtn.innerHTML;
            likeBtn.innerHTML = '<span class="spinner"></span>';

            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update all like buttons for this post
                    updateAllLikeButtons(postId, data.liked, data.count, data.text);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Failed to update like', 'error');
                }

            } catch (error) {
                console.error('Like error:', error);
                showToast('Network error. Please try again.', 'error');
            } finally {
                // Re-enable button
                likeBtn.disabled = false;
                likeBtn.innerHTML = originalHTML;
            }
        });
    });

    function updateAllLikeButtons(postId, liked, count, text) {
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            // Update like buttons
            const likeButtons = postFooter.querySelectorAll('.like-btn');
            likeButtons.forEach(btn => {
                btn.classList.toggle('active', liked);

                const icon = btn.querySelector('.like-icon');
                if (icon) {
                    icon.className = liked ? 'fas fa-thumbs-up' : 'far fa-thumbs-up';
                }

                const label = btn.querySelector('span:not(.spinner)');
                if (label) {
                    label.textContent = liked ? 'Liked' : 'Like';
                }
            });

            // Update like count display
            const likeText = postFooter.querySelector('.like-text');
            if (likeText) {
                likeText.textContent = text;
            }

            // Update like emoji
            const likeEmoji = postFooter.querySelector('.like-emoji');
            if (likeEmoji) {
                likeEmoji.style.display = count > 0 ? 'inline-flex' : 'none';
            }
        });
    }

    // ========== COMMENT FUNCTIONALITY ==========
    // Toggle comment input area
    document.querySelectorAll('.comment-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const inputArea = document.getElementById(`comment-input-${postId}`);

            if (inputArea) {
                // Hide all other comment areas
                document.querySelectorAll('.comment-input-area').forEach(area => {
                    if (area !== inputArea) {
                        area.style.display = 'none';
                    }
                });

                // Toggle current area
                const isHidden = inputArea.style.display === 'none';
                inputArea.style.display = isHidden ? 'block' : 'none';

                // Focus textarea if showing
                if (isHidden) {
                    const textarea = inputArea.querySelector('.comment-textarea');
                    if (textarea) {
                        setTimeout(() => {
                            textarea.focus();
                            setCommentPlaceholder(textarea);
                        }, 100);
                        inputArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        });
    });

    // Set dynamic placeholder text
    function setCommentPlaceholder(textarea) {
        const placeholders = [
            "Write a comment...",
            "What's on your mind?",
            "Share your thoughts...",
            "Add a comment...",
            "Leave a reply...",
            "Say something nice...",
            "Join the conversation..."
        ];

        const randomPlaceholder = placeholders[Math.floor(Math.random() * placeholders.length)];
        textarea.placeholder = randomPlaceholder;
    }

    // Comment form submission
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const postId = this.getAttribute('data-post-id');
            const textarea = this.querySelector('.comment-textarea');
            const content = textarea ? textarea.value.trim() : '';

            if (!content) {
                showToast('Please write a comment', 'error');
                return;
            }

            if (content.length > 5000) {
                showToast('Comment is too long (max 5000 characters)', 'error');
                return;
            }

            const submitBtn = this.querySelector('.send-comment-btn');
            if (!submitBtn) return;

            submitBtn.disabled = true;
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner"></span>';

            try {
                const response = await fetch(`/posts/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                });

                const data = await response.json();

                if (data.success) {
                    // Clear textarea
                    if (textarea) {
                        textarea.value = '';
                        textarea.style.height = 'auto';
                        textarea.placeholder = 'Write a comment...';
                    }

                    // Hide comment input area
                    const inputArea = document.getElementById(`comment-input-${postId}`);
                    if (inputArea) {
                        inputArea.style.display = 'none';
                    }

                    // Add new comment
                    addNewCommentToAllPosts(postId, data.comment);

                    // Update comment count
                    updateCommentCountAllPosts(postId, 'increment');

                    // Update loaded comments count
                    if (postData[postId]) {
                        postData[postId].loadedComments++;
                    }

                    showToast('Comment posted successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to post comment', 'error');
                }

            } catch (error) {
                console.error('Comment error:', error);
                showToast('Network error. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        });
    });

    // VIEW MORE COMMENTS - Always loads 3 comments
    document.addEventListener('click', async function(e) {
        const target = e.target;
        const isViewMoreBtn = target.classList.contains('view-more-comments') ||
                              target.closest('.view-more-comments');

        if (isViewMoreBtn) {
            e.preventDefault();

            const btn = target.classList.contains('view-more-comments')
                ? target
                : target.closest('.view-more-comments');

            const postId = btn.getAttribute('data-post-id');

            if (!postId || postData[postId]?.isLoading) return;

            // Set loading state
            postData[postId].isLoading = true;
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner"></span> Loading comments...';

            try {
                // Get current offset (always load from current position)
                const currentOffset = postData[postId].loadedComments || 0;

                const response = await fetch(`/posts/${postId}/comments/${currentOffset}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.comments && data.comments.length > 0) {
                    // Store scroll position before adding comments
                    postData[postId].scrollPosition = window.scrollY;

                    // Always load exactly 3 comments (or less if not available)
                    const commentsToLoad = data.comments.slice(0, 3);

                    // Add comments to all instances of this post
                    commentsToLoad.forEach(comment => {
                        addCommentToAllPosts(postId, comment, false);
                    });

                    // Update loaded count
                    postData[postId].loadedComments += commentsToLoad.length;

                    // Get total comments
                    const totalComments = getTotalCommentCount(postId);

                    // Update button state
                    updateCommentButtonState(postId, btn, totalComments);

                    // Show success message
                    showToast(`Loaded ${commentsToLoad.length} more comments`, 'success');
                } else {
                    // No more comments
                    btn.remove();
                    showToast('No more comments to load', 'info');
                }

            } catch (error) {
                console.error('Load comments error:', error);
                showToast('Failed to load comments', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            } finally {
                postData[postId].isLoading = false;
            }
        }
    });

    // SHOW LESS COMMENTS - Scrolls back to original position
    document.addEventListener('click', function(e) {
        const target = e.target;
        const isShowLessBtn = target.classList.contains('show-less-comments') ||
                              target.closest('.show-less-comments');

        if (isShowLessBtn) {
            e.preventDefault();

            const btn = target.classList.contains('show-less-comments')
                ? target
                : target.closest('.show-less-comments');

            const postId = btn.getAttribute('data-post-id');

            if (!postId) return;

            // Store the post element to scroll back to it
            const postElement = document.querySelector(`.post-footer[data-post-id="${postId}"]`);
            if (!postElement) return;

            // Store current scroll position before hiding comments
            const currentScroll = window.scrollY;
            const postTop = postElement.getBoundingClientRect().top + window.scrollY;

            // Hide all but first comment in all post instances
            hideComments(postId);

            // Reset loaded comments count
            postData[postId].loadedComments = 1;

            // Remove show less button
            btn.remove();

            // Show view more button again
            showViewMoreButton(postId);

            // Scroll back to the post's comment section
            setTimeout(() => {
                const commentsSection = document.getElementById(`comments-${postId}`);
                if (commentsSection) {
                    // Calculate where to scroll - either original position or post top
                    const scrollTo = Math.min(currentScroll, postTop);
                    window.scrollTo({
                        top: scrollTo,
                        behavior: 'smooth'
                    });
                }
            }, 100);

            showToast('Showing fewer comments', 'info');
        }
    });

    // Delete comment
    document.addEventListener('click', async function(e) {
        const deleteBtn = e.target.classList.contains('delete-comment')
            ? e.target
            : e.target.closest('.delete-comment');

        if (deleteBtn) {
            const commentId = deleteBtn.getAttribute('data-comment-id');

            if (!commentId) return;

            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            deleteBtn.disabled = true;
            const originalText = deleteBtn.textContent;
            deleteBtn.textContent = 'Deleting...';

            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Remove comment from all posts
                    removeCommentFromAllPosts(commentId);
                    showToast('Comment deleted', 'success');
                } else {
                    showToast(data.message || 'Failed to delete comment', 'error');
                    deleteBtn.textContent = originalText;
                }

            } catch (error) {
                console.error('Delete error:', error);
                showToast('Network error. Please try again.', 'error');
                deleteBtn.textContent = originalText;
            } finally {
                deleteBtn.disabled = false;
            }
        }
    });

    // Auto-resize textarea
    document.querySelectorAll('.comment-textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });
    });

    // ========== HELPER FUNCTIONS ==========

    function addNewCommentToAllPosts(postId, comment) {
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            const commentsList = postFooter.querySelector('.comments-list');
            if (!commentsList) return;

            addCommentElement(commentsList, comment, true);
        });
    }

    function addCommentToAllPosts(postId, comment, prepend = false) {
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            const commentsList = postFooter.querySelector('.comments-list');
            if (!commentsList) return;

            // Check if comment already exists
            const existing = commentsList.querySelector(`[data-id="${comment.id}"]`);
            if (existing) return;

            addCommentElement(commentsList, comment, prepend);
        });
    }

    function addCommentElement(commentsList, comment, prepend) {
        const commentElement = document.createElement('div');
        commentElement.className = 'comment-item';
        commentElement.setAttribute('data-id', comment.id);

        const avatarHTML = comment.user_avatar
            ? `<img src="${comment.user_avatar}" alt="${comment.user_name}" width="32" height="32">`
            : '<i class="fas fa-user-circle"></i>';

        commentElement.innerHTML = `
            <div class="comment-avatar">${avatarHTML}</div>
            <div class="comment-content">
                <div class="comment-header">
                    <strong>${comment.user_name}</strong>
                    <span class="comment-time">${comment.created_at}</span>
                </div>
                <div class="comment-text">${comment.content}</div>
                ${comment.can_delete ? `
                    <button class="delete-comment" data-comment-id="${comment.id}">
                        Delete
                    </button>
                ` : ''}
            </div>
        `;

        if (prepend) {
            commentsList.prepend(commentElement);
        } else {
            commentsList.appendChild(commentElement);
        }
    }

    function updateCommentCountAllPosts(postId, action) {
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            const commentStat = postFooter.querySelector('.comment-count');
            if (commentStat) {
                const countSpan = commentStat.querySelector('span');
                if (countSpan) {
                    let currentCount = parseInt(countSpan.textContent) || 0;

                    if (action === 'increment') {
                        currentCount++;
                    } else if (action === 'decrement') {
                        currentCount = Math.max(0, currentCount - 1);
                    }

                    if (currentCount === 0) {
                        commentStat.style.display = 'none';
                    } else {
                        commentStat.style.display = 'block';
                        countSpan.textContent = currentCount === 1
                            ? '1 comment'
                            : `${currentCount} comments`;
                    }
                }
            }
        });
    }

    function getTotalCommentCount(postId) {
        const firstPost = document.querySelector(`.post-footer[data-post-id="${postId}"]`);
        if (!firstPost) return 0;

        const commentStat = firstPost.querySelector('.comment-count span');
        if (commentStat) {
            const text = commentStat.textContent;
            const match = text.match(/(\d+)/);
            return match ? parseInt(match[0]) : 0;
        }
        return 0;
    }

    function updateCommentButtonState(postId, btn, totalComments) {
        const loaded = postData[postId].loadedComments || 0;
        const remaining = totalComments - loaded;

        if (remaining <= 0) {
            // All comments loaded
            btn.remove();

            // Add show less button if we have more than 1 comment
            if (totalComments > 1) {
                addShowLessButton(postId, btn.parentNode);
            }
        } else {
            // Update button text (always says "View more comments")
            btn.innerHTML = '<i class="fas fa-chevron-down me-1"></i> View more comments';
            btn.disabled = false;

            // Add show less button if we have more than 1 comment loaded
            if (loaded > 1) {
                // Check if show less button already exists
                const buttonsContainer = btn.closest('.comment-buttons') || btn.parentNode;
                const existingShowLess = buttonsContainer.querySelector('.show-less-comments');
                if (!existingShowLess) {
                    addShowLessButton(postId, buttonsContainer);
                }
            }
        }
    }

    function addShowLessButton(postId, container) {
        // Remove existing show less button
        const existing = container.querySelector('.show-less-comments');
        if (existing) existing.remove();

        const showLessBtn = document.createElement('button');
        showLessBtn.className = 'show-less-comments';
        showLessBtn.setAttribute('data-post-id', postId);
        showLessBtn.innerHTML = '<i class="fas fa-chevron-up me-1"></i> Show less';

        // Insert after view more button or at the end
        const viewMoreBtn = container.querySelector('.view-more-comments');
        if (viewMoreBtn) {
            viewMoreBtn.insertAdjacentElement('afterend', showLessBtn);
        } else {
            container.appendChild(showLessBtn);
        }
    }

    function hideComments(postId) {
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            const commentsList = postFooter.querySelector('.comments-list');
            if (!commentsList) return;

            const allComments = Array.from(commentsList.children);

            // Keep only first comment
            for (let i = 1; i < allComments.length; i++) {
                allComments[i].remove();
            }
        });
    }

    function showViewMoreButton(postId) {
        // Find all instances of this post
        document.querySelectorAll(`.post-footer[data-post-id="${postId}"]`).forEach(postFooter => {
            const buttonsContainer = postFooter.querySelector('.comment-buttons');
            if (!buttonsContainer) return;

            // Remove existing view more button
            const existingViewMore = buttonsContainer.querySelector('.view-more-comments');
            if (existingViewMore) existingViewMore.remove();

            // Create new view more button
            const viewMoreBtn = document.createElement('button');
            viewMoreBtn.className = 'view-more-comments';
            viewMoreBtn.setAttribute('data-post-id', postId);
            viewMoreBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i> View more comments';

            buttonsContainer.prepend(viewMoreBtn);
        });
    }

    function removeCommentFromAllPosts(commentId) {
        document.querySelectorAll(`.comment-item[data-id="${commentId}"]`).forEach(comment => {
            const postFooter = comment.closest('.post-footer');
            comment.remove();

            if (postFooter) {
                const postId = postFooter.getAttribute('data-post-id');
                updateCommentCountAllPosts(postId, 'decrement');

                // Update loaded comments count
                if (postData[postId]) {
                    postData[postId].loadedComments = Math.max(1, postData[postId].loadedComments - 1);
                }

                // Update button state
                const totalComments = getTotalCommentCount(postId);
                const btn = document.querySelector(`.view-more-comments[data-post-id="${postId}"]`);
                if (btn && totalComments > 0) {
                    updateCommentButtonState(postId, btn, totalComments);
                }
            }
        });
    }

    // Initialize all post buttons
    function initializeAllPosts() {
        document.querySelectorAll('.post-footer').forEach(post => {
            const postId = post.getAttribute('data-post-id');
            const totalComments = getTotalCommentCount(postId);

            // Update view more buttons for all posts
            const viewMoreBtn = post.querySelector('.view-more-comments');
            if (viewMoreBtn && totalComments > 1) {
                updateCommentButtonState(postId, viewMoreBtn, totalComments);
            }
        });
    }

    // Run initialization
    initializeAllPosts();
});
