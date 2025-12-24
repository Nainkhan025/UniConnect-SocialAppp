{{-- ================= HEADER ================= --}}
<div class="post-header d-flex align-items-center justify-content-between">

    <div class="d-flex align-items-center gap-2">
        {{-- PROFILE IMAGE / ICON --}}
        @if($post->user->profile_photo ?? false)
            <img
                src="{{ asset('storage/profile_photos/'.$post->user->profile_photo) }}"
                class="post-avatar"
            >
        @else
            <i class="bi bi-person-circle fs-2 text-secondary"></i>
        @endif

        {{-- NAME + TIME --}}
        <div>
            <strong>{{ $post->user->name ?? 'User' }}</strong><br>
            <small class="text-muted">
                {{ $post->created_at->diffForHumans() }}
            </small>
        </div>
    </div>

    {{-- DOTS MENU --}}
    <a href="#" class="text-muted fs-4 text-decoration-none">
        <i class="bi bi-three-dots"></i>
    </a>

</div>


{{-- ================= BODY ================= --}}
<div class="post-body">

    {{-- POST TEXT --}}
    @if($post->content)
        <p class="post-content">{{ $post->content }}</p>
    @endif

    {{-- MEDIA --}}
    @if($post->media)
        @php
            $ext = strtolower(pathinfo($post->media, PATHINFO_EXTENSION));
            $images = ['jpg','jpeg','png','gif','webp'];
            $videos = ['mp4','webm','ogg'];
        @endphp

        <div class="post-media">

            {{-- VIDEO --}}
            @if(in_array($ext, $videos))
                <video
                    class="post-video"
                    controls
                    playsinline
                    preload="metadata"
                >
                    <source src="{{ asset('storage/media_post/'.$post->media) }}" type="video/{{ $ext }}">
                </video>

            {{-- IMAGE --}}
            @elseif(in_array($ext, $images))
                <img
                    src="{{ asset('storage/media_post/'.$post->media) }}"
                    class="post-image"
                    alt="Post image"
                >
            @endif

        </div>
    @endif

</div>



{{-- POST FOOTER SECTION --}}
<div class="post-footer" data-post-id="{{ $post->id }}">

    {{-- LIKE COUNT --}}
    @php
        $likesCount = $post->likes->count();
        $liked = $post->isLikedBy(auth()->user());
    @endphp

    <div class="post-stats mb-2">
        @if($likesCount > 0)
            <div class="like-count">
                <span class="like-emoji">👍</span>
                <span class="like-text">
                    @if($liked)
                        @if($likesCount == 1)
                            You
                        @elseif($likesCount == 2)
                            You and 1 other
                        @else
                            You and {{ $likesCount - 1 }} others
                        @endif
                    @else
                        {{ $likesCount }}
                    @endif
                </span>
            </div>
        @endif

        @if($post->comments->count() > 0)
            <div class="comment-count">
                <span>{{ $post->comments->count() }} comments</span>
            </div>
        @endif
    </div>

    <hr>

    {{-- ACTION BUTTONS --}}
    <div class="post-actions">
        <button class="action-btn like-btn {{ $liked ? 'active' : '' }}" data-post-id="{{ $post->id }}">
            <i class="like-icon {{ $liked ? 'fas' : 'far' }} fa-thumbs-up"></i>
            <span>{{ $liked ? 'Liked' : 'Like' }}</span>
        </button>

        <button class="action-btn comment-toggle-btn" data-post-id="{{ $post->id }}">
            <i class="far fa-comment"></i>
            <span>Comment</span>
        </button>

        <button class="action-btn share-btn">
            <i class="fas fa-share"></i>
            <span>Share</span>
        </button>
    </div>

    {{-- COMMENT INPUT AREA (HIDDEN BY DEFAULT) --}}
        <div class="comment-input-area" id="comment-input-{{ $post->id }}" style="display: none;">
            <div class="comment-input-wrapper">
                <div class="current-user-avatar">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                             alt="{{ auth()->user()->name }}"
                             width="40"
                             height="40">
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endif
                </div>

                <form class="comment-form" data-post-id="{{ $post->id }}">
                    <div class="comment-form-header">
                        <span class="current-user-name">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="textarea-wrapper">
                        <textarea
                            name="content"
                            class="comment-textarea"
                            placeholder="Comment as {{ auth()->user()->name }}..."
                            rows="2"
                            data-placeholder-base="Comment as {{ auth()->user()->name }}..."></textarea>
                        <button type="submit" class="send-comment-btn" title="Post comment">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    {{-- CHARACTER COUNTER (OPTIONAL) --}}
                    <div class="char-counter" style="display: none; font-size: 12px; color: #65676b; text-align: right; margin-top: 5px;">
                        <span class="char-count">0</span>/5000
                    </div>
                </form>
            </div>
        </div>

    {{-- COMMENTS SECTION --}}
    <div class="comments-section" id="comments-{{ $post->id }}">

        {{-- SHOW FIRST COMMENT ONLY --}}
        @if($post->comments->count() > 0)
            <div class="comments-list">
                @foreach($post->comments->take(1) as $comment)
                    <div class="comment-item" data-id="{{ $comment->id }}">
                        <div class="comment-avatar">
                            @if($comment->user->profile_photo)
                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                     alt="{{ $comment->user->name }}"
                                     width="32"
                                     height="32">
                            @else
                                <i class="fas fa-user-circle"></i>
                            @endif
                        </div>
                        <div class="comment-content">
                            <div class="comment-header">
                                <strong class="comment-username">{{ $comment->user->name }}</strong>
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="comment-text">{{ $comment->content }}</div>
                            @if(auth()->id() == $comment->user_id)
                                <button class="delete-comment" data-comment-id="{{ $comment->id }}">
                                    Delete
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- COMMENT BUTTONS (VIEW MORE/SHOW LESS) --}}
            @if($post->comments->count() > 1)
                <div class="comment-buttons">
                    <button class="view-more-comments" data-post-id="{{ $post->id }}">
                        <i class="fas fa-chevron-down me-1"></i> View more comments
                    </button>
                    {{-- Show Less button will be added dynamically --}}
                </div>
            @endif
        @endif



    </div>

</div>
