@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/posts.css') }}">

<div class="container mt-4 w-75">

    @include('posts.partials.alert')

    @include('posts.partials.create-card')

    {{-- Posts Feed --}}
    @forelse ($posts as $post)
        @include('posts.partials.post-card', ['post' => $post])
    @empty
        <p class="text-muted text-center">No posts yet. Be the first to post something!</p>
    @endforelse

</div>

@include('posts.partials.create-modal')
@include('posts.partials.light-box')

<script src="{{ asset('js/posts.js') }}"></script>
@endsection
