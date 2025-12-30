{{-- ================= HEADER ================= --}}
<div class="post-header d-flex align-items-center justify-content-between">

    <div class="d-flex align-items-center gap-2">
        {{-- PROFILE IMAGE / ICON --}}
        @if ($post->user->profile_photo ?? false)
            <img src="{{ asset('storage/profile_photos/' . $post->user->profile_photo) }}" class="post-avatar">
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
    @if ($post->content)
        <p class="post-content">{{ $post->content }}</p>
    @endif

    {{-- MEDIA --}}
    @if ($post->media)
        @php
            $ext = strtolower(pathinfo($post->media, PATHINFO_EXTENSION));
            $images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $videos = ['mp4', 'webm', 'ogg'];
        @endphp

        <div class="post-media">

            {{-- VIDEO --}}
            @if (in_array($ext, $videos))
                <video class="post-video" controls playsinline preload="metadata">
                    <source src="{{ asset('storage/media_post/' . $post->media) }}" type="video/{{ $ext }}">
                </video>

                {{-- IMAGE --}}
            @elseif(in_array($ext, $images))
                <img src="{{ asset('storage/media_post/' . $post->media) }}" class="post-image" alt="Post image">
            @endif

        </div>
    @endif

</div>

{{-- Replace $post with your actual post variable --}}

<div class="post-footer" data-post-id="{{ $post->id }}">
    <div class="post-stats">
        <div class="likes-stat" id="likesStat-{{ $post->id }}">
            @php
                $likesCount = $post->likes->count();
                $isLiked = $post->likes->where('user_id', auth()->id())->isNotEmpty();
            @endphp
            @if ($likesCount > 0)
                <span class="likes-count" data-post-id="{{ $post->id }}">
                    <span class="likes-icon-small">👍</span>
                    <span class="count-text">{{ $likesCount }}</span>
                </span>
            @endif
        </div>
        <div class="comments-stat" id="commentsStat-{{ $post->id }}">
            @php
                $commentsCount = $post->comments->count();
            @endphp
            @if ($commentsCount > 0)
                <span class="comments-count">{{ $commentsCount }}
                    {{ $commentsCount == 1 ? 'comment' : 'comments' }}</span>
            @endif
        </div>
    </div>

    <div class="post-actions">
        <button class="action-btn like-btn {{ $isLiked ? 'liked' : '' }}" data-post-id="{{ $post->id }}"
            type="button">
            <span class="action-icon">👍</span>
            <span class="action-text">Like</span>
        </button>
        <button class="action-btn comment-btn" data-post-id="{{ $post->id }}" type="button">
            <span class="action-icon">💬</span>
            <span class="action-text">Comment</span>
        </button>
    </div>
</div>
