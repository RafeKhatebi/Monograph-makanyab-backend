<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = Post::query()
            ->published()
            ->with('user:id,name')
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('excerpt', 'like', '%'.$search.'%');
                });
            })
            ->latest('published_at')
            ->paginate(min($request->integer('per_page', 12), 50));

        return response()->json($posts);
    }

    public function show(Post $post): JsonResponse
    {
        if (! $post->is_published || ! $post->published_at || $post->published_at->isFuture()) {
            return response()->json(['message' => __('messages.api.post_not_found')], 404);
        }

        return response()->json($post->load('user:id,name'));
    }
}
