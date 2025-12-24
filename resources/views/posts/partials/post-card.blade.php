{{-- POST CARD --}}
<div class="card">

    {{-- HEADER --}}
    <div class="card-header">
        <img src="{{ asset('images/user.png') }}" class="post-avatar">
        <div>
            <strong>{{ $post->user->name ?? 'User' }}</strong><br>
            <small>{{ $post->created_at->diffForHumans() }}</small>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- TEXT --}}
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
                        class="post-video js-video"
                        controls
                        muted
                        preload="metadata"
                        playsinline
                    >
                        <source src="{{ asset('storage/media_post/'.$post->media) }}" type="video/{{ $ext }}">
                        Your browser does not support the video tag.
                    </video>

                {{-- IMAGE --}}
                @elseif(in_array($ext, $images))
                  <img
                    src="{{ asset('storage/media_post/'.$post->media) }}"
                     class="post-image js-lightbox"
                     alt="Post image"
                       >

                @endif

            </div>
        @endif

    </div>
</div>
