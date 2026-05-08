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
            ->where('is_published', true)
            ->when($request->query('search'), function ($query, $search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%');
            })
            ->latest('published_at')
            ->paginate($request->integer('per_page', 12));

        return response()->json($posts);
    }

    public function show(Post $post): JsonResponse
    {
        if (! $post->is_published) {
            return response()->json(['message' => 'Post not found.'], 404);
        }

        return response()->json($post);
    }
}
