{{-- ================= POST CARD ================= --}}
<div class="card mb-3 shadow-sm rounded" data-post-id="{{ $post->id }}">
    {{-- ================= HEADER ================= --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            {{-- PROFILE IMAGE / ICON --}}
            @if ($post->user->profile_photo ?? false)
                <img src="{{ asset('storage/profile_photos/' . $post->user->profile_photo) }}"
                    class="post-avatar rounded-circle" width="48" height="48" alt="Profile">
            @else
                <i class="bi bi-person-circle fs-2 text-secondary"></i>
            @endif

            {{-- NAME + TIME --}}
            <div class="d-flex flex-column">
                <strong>{{ $post->user->name ?? 'User' }}</strong>
                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
            </div>
        </div>

        {{-- DOTS MENU --}}
        <a href="#" class="text-muted fs-4 text-decoration-none">
            <i class="bi bi-three-dots"></i>
        </a>
    </div>

    {{-- ================= BODY ================= --}}
    <div class="card-body">
        {{-- POST TEXT --}}
        @if ($post->content)
            <p class="post-content mb-3">{{ $post->content }}</p>
        @endif

        {{-- MEDIA --}}
        @if ($post->media)
            @php
                $ext = strtolower(pathinfo($post->media, PATHINFO_EXTENSION));
                $images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $videos = ['mp4', 'webm', 'ogg'];
            @endphp

            @if (in_array($ext, $images))
                <img src="{{ asset('storage/media_post/' . $post->media) }}" class="post-image img-fluid rounded mb-3"
                    alt="Post image">
            @elseif(in_array($ext, $videos))
                <video class="post-video w-100 rounded mb-3" controls>
                    <source src="{{ asset('storage/media_post/' . $post->media) }}" type="video/{{ $ext }}">
                </video>
            @endif
        @endif
    </div>

    {{-- ================= STATS ================= --}}
    <div class="px-3 pb-2">
        <div class="d-flex justify-content-between text-muted mb-2 small">
            <div id="likesStat-{{ $post->id }}">
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
            <div id="commentsStat-{{ $post->id }}">
                @php
                    $commentsCount = $post->comments->count();
                @endphp
                @if ($commentsCount > 0)
                    <span class="comments-count">{{ $commentsCount }}
                        {{ $commentsCount == 1 ? 'comment' : 'comments' }}</span>
                @endif
            </div>
        </div>
        <hr class="my-1">
    </div>

    {{-- ================= ACTION BUTTONS ================= --}}
    <div class="card-body pt-0 d-flex post-actions">
        <button class="action-btn like-btn {{ $isLiked ? 'liked' : '' }}" data-post-id="{{ $post->id }}"
            type="button">
            <span class="action-icon">👍</span>
            <span class="action-text">{{ $isLiked ? 'Liked' : 'Like' }}</span>
        </button>
        <button class="action-btn comment-btn" data-post-id="{{ $post->id }}" type="button">
            <span class="action-icon">💬</span>
            <span class="action-text">Comment</span>
        </button>
    </div>
</div>
