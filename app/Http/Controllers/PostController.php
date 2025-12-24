<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostModel;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Display posts
    public function index()
    {
        $posts = PostModel::latest()->with('user')->get();
        return view('posts.index', compact('posts'));
    }

    // Store post
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'media'   => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,webp,mp4,mov,avi,wmv,flv,mkv|max:20480', // max 20MB
        ]);

        $mediaName = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('media_post', $mediaName, 'public'); // store in public disk
        }

        if (!$request->content && !$mediaName) {
            return redirect()->back()->with('error', 'Please add content or upload media!');
        }

        PostModel::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'media'   => $mediaName,
        ]);

        return redirect()->back()->with('success', 'Post Created Successfully!');
    }

    // Delete post
    public function destroy(PostModel $post)
    {
        if ($post->media && Storage::disk('public')->exists('media_post/' . $post->media)) {
            Storage::disk('public')->delete('media_post/' . $post->media);
        }

        $post->delete();
        return redirect()->back()->with('success', 'Post Deleted Successfully!');
    }

    // Edit post
    public function edit(PostModel $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    // Update post
    public function update(Request $request, PostModel $post)
    {
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'media'   => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,webp,mp4,mov,avi,wmv,flv,mkv|max:20480',
        ]);

        // Delete old media if new uploaded
        if ($request->hasFile('media')) {
            if ($post->media && Storage::disk('public')->exists('media_post/' . $post->media)) {
                Storage::disk('public')->delete('media_post/' . $post->media);
            }

            $file = $request->file('media');
            $mediaName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('media_post', $mediaName, 'public'); // store in public disk
            $post->media = $mediaName;
        }

        $post->content = $request->content;
        $post->save();

        return redirect()->back()->with('success', 'Post Updated Successfully!');
    }
}
