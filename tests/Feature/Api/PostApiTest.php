<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
});

test('anyone can list published posts', function () {
    Post::factory()->count(3)->create(['is_published' => true, 'user_id' => $this->user->id]);
    Post::factory()->create(['is_published' => false, 'user_id' => $this->user->id]);

    $this->getJson('/api/posts')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('anyone can view a published post', function () {
    $post = Post::factory()->create(['is_published' => true, 'user_id' => $this->user->id]);

    $this->getJson("/api/posts/{$post->slug}")
        ->assertOk()
        ->assertJsonFragment(['id' => $post->id]);
});

test('unpublished post returns 404', function () {
    $post = Post::factory()->create(['is_published' => false, 'user_id' => $this->user->id]);

    $this->getJson("/api/posts/{$post->slug}")
        ->assertNotFound();
});

test('posts can be searched by title', function () {
    Post::factory()->create(['title' => 'Laravel Tips', 'is_published' => true, 'user_id' => $this->user->id]);
    Post::factory()->create(['title' => 'PHP Best Practices', 'is_published' => true, 'user_id' => $this->user->id]);

    $this->getJson('/api/posts?search=Laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('posts are ordered by published_at', function () {
    Post::factory()->create(['published_at' => now()->subDays(2), 'is_published' => true, 'user_id' => $this->user->id]);
    Post::factory()->create(['published_at' => now(), 'is_published' => true, 'user_id' => $this->user->id]);

    $response = $this->getJson('/api/posts');
    $data = $response->json('data');

    $this->assertTrue(strtotime($data[0]['published_at']) > strtotime($data[1]['published_at']));
});
