<!-- resources/views/includes/head.blade.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniConnect</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Bootstrap FIRST -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- Custom CSS LAST -->
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/feed.css') }}">
<link rel="stylesheet" href="{{ asset('css/post.css') }}">
<link rel="stylesheet" href="{{ asset('css/create-post.css') }}">
<link rel="stylesheet" href="{{ asset('css/comments-modal.css') }}">


<meta name="csrf-token" content="{{ csrf_token() }}">
