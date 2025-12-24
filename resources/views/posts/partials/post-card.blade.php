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



{{-- ================= FOOTER ================= --}}
<div class="post-footer">

    {{-- LIKE COUNT --}}
    <div class="like-count text-muted mb-2">
        👍 {{ $post->likes_count ?? 4 }}
    </div>

    <hr class="m-0">

    {{-- ACTION BUTTONS --}}
    <div class="post-actions d-flex justify-content-between text-center">

        <a href="#" class="post-action">
            <i class="bi bi-hand-thumbs-up"></i> Like
        </a>

        <a href="#" class="post-action">
            <i class="bi bi-chat"></i> Comment
        </a>

        <a href="#" class="post-action">
            <i class="bi bi-share"></i> Share
        </a>

    </div>

</div>
