<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostModel;
use App\Models\Like;
use App\Models\Comment;

class PostInteractionController extends Controller
{
    // LIKE TOGGLE
    public function like(PostModel $post)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first'
                ], 401);
            }

            $existingLike = $post->likes()->where('user_id', $user->id)->first();

            if ($existingLike) {
                $existingLike->delete();
                $liked = false;
                $message = 'Like removed';
            } else {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id
                ]);
                $liked = true;
                $message = 'Post liked';
            }

            $count = $post->likes()->count();
            $text = $this->likeText($count, $liked);

            return response()->json([
                'success' => true,
                'message' => $message,
                'liked' => $liked,
                'count' => $count,
                'text' => $text
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function likeText($count, $liked)
    {
        if ($count === 0) return 'Like';

        if ($liked) {
            if ($count === 1) return 'You like this';
            if ($count === 2) return 'You and 1 other';
            return 'You and ' . ($count - 1) . ' others';
        }

        if ($count === 1) return '1 like';
        return $count . ' likes';
    }

    // ADD COMMENT
    public function comment(Request $request, PostModel $post)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first'
                ], 401);
            }

            // Simple validation
            $content = trim($request->content);
            if (empty($content)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment cannot be empty'
                ], 400);
            }

            // Create comment
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => $content
            ]);

            // Get fresh comment with user data
            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Comment posted',
                'comment' => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $this->getUserAvatar($comment->user),
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_delete' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // DELETE COMMENT
    public function deleteComment(Comment $comment)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first'
                ], 401);
            }

            // Check permission
            if ($user->id !== $comment->user_id && !$user->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // GET MORE COMMENTS
    public function getComments(PostModel $post, $offset = 0)
    {
        try {
            $comments = $post->comments()
                ->with('user')
                ->latest()
                ->skip($offset)
                ->take(5)
                ->get();

            return response()->json([
                'success' => true,
                'comments' => $comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'user_name' => $comment->user->name,
                        'user_avatar' => $this->getUserAvatar($comment->user),
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'can_delete' => auth()->id() === $comment->user_id
                    ];
                }),
                'has_more' => $post->comments()->count() > ($offset + 5)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper to get user avatar
    private function getUserAvatar($user)
    {
        if ($user->profile_photo) {
            return asset('storage/' . $user->profile_photo);
        }
        return null; // Will use default icon in frontend
    }
}
