<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('user:id,name')
            ->latest('published_at')
            ->paginate(6);

        $recentPosts = Post::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('pages.posts.index', compact('posts', 'recentPosts'));
    }

    public function show(Post $post)
    {
        abort_if(! $post->is_published || ! $post->published_at || $post->published_at->isFuture(), 404);

        $post->load('user:id,name');

        $recentPosts = Post::where('id', '!=', $post->id)
            ->published()
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('pages.posts.show', compact('post', 'recentPosts'));
    }
}
