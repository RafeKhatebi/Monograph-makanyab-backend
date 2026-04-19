<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->latest()
            ->paginate(6);

        $recentPosts = Post::where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.posts.index', compact('posts', 'recentPosts'));
    }

    public function show(Post $post)
    {
        abort_if(! $post->is_published, 404);

        $recentPosts = Post::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.posts.show', compact('post', 'recentPosts'));
    }
}
