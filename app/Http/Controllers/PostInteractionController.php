<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    public function like(PostModel $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => auth()->id(), 'post_id' => $post->id]);
            $liked = true;
        }

        $likes = $post->likes()->with('user')->get();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $likes->count(),
            'likes' => $likes->map(fn ($l) => [
                'id' => $l->id,
                'user_id' => $l->user_id,
                'user_name' => $l->user->name,
                'user_avatar' => $l->user->profile_photo ? asset('storage/' . $l->user->profile_photo) : null
            ])
        ]);
    }

    public function likers(PostModel $post)
    {
        $likes = $post->likes()
            ->with('user')
            ->latest()
            ->get()
            ->map(fn ($l) => [
                'id' => $l->id,
                'user_id' => $l->user_id,
                'user_name' => $l->user->name,
                'user_avatar' => $l->user->profile_photo ? asset('storage/' . $l->user->profile_photo) : null
            ]);

        return response()->json([
            'success' => true,
            'likes' => $likes,
            'count' => $likes->count()
        ]);
    }

    public function comments(PostModel $post)
    {
        $comments = $post->comments()
            ->with('user')
            ->latest()
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'user_id' => $c->user_id,
                'user_name' => $c->user->name,
                'user_avatar' => $c->user->profile_photo ? asset('storage/' . $c->user->profile_photo) : null,
                'content' => $c->content,
                'created_at' => $c->created_at->diffForHumans()
            ]);

        $likes = $post->likes()->with('user')->get();
        $isLiked = $post->likes()->where('user_id', auth()->id())->exists();

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'likes_count' => $likes->count(),
            'is_liked' => $isLiked,
            'likes' => $likes->map(fn ($l) => [
                'id' => $l->id,
                'user_id' => $l->user_id,
                'user_name' => $l->user->name,
                'user_avatar' => $l->user->profile_photo ? asset('storage/' . $l->user->profile_photo) : null
            ])
        ]);
    }

    public function comment(Request $request, PostModel $post)
    {
        $request->validate(['content' => 'required|max:5000']);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'content' => $request->content
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->name,
                'user_avatar' => $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : null,
                'content' => $comment->content,
                'created_at' => 'Just now'
            ]
        ]);
    }

    public function deleteComment(Comment $comment)
    {
        abort_if(auth()->id() !== $comment->user_id, 403);
        $comment->delete();

        return response()->json(['success' => true]);
    }
}