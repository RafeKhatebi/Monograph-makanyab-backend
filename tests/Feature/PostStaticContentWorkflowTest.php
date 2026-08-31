<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public posts only show currently published posts', function () {
    $author = User::factory()->create();
    $published = Post::factory()->create([
        'user_id' => $author->id,
        'title' => 'Visible Article',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);
    $draft = Post::factory()->unpublished()->create(['user_id' => $author->id]);
    $future = Post::factory()->create([
        'user_id' => $author->id,
        'title' => 'Future Article',
        'is_published' => true,
        'published_at' => now()->addDay(),
    ]);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Visible Article')
        ->assertDontSee($draft->title)
        ->assertDontSee('Future Article');

    $this->get(route('posts.show', $published->slug))->assertOk();
    $this->get(route('posts.show', $draft->slug))->assertNotFound();
    $this->get(route('posts.show', $future->slug))->assertNotFound();
});

test('admin post creation generates unique slugs and leaves drafts unpublished', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Post::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);

    $this->actingAs($admin)
        ->post(route('admin.posts.store'), [
            'title' => 'Duplicate Title',
            'excerpt' => 'Short summary',
            'content' => 'Body text',
        ])
        ->assertRedirect(route('admin.posts.index'));

    $this->assertDatabaseHas('posts', [
        'title' => 'Duplicate Title',
        'slug' => 'duplicate-title-1',
        'is_published' => false,
        'published_at' => null,
    ]);
});

test('static legal pages include SEO metadata', function () {
    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('name="description"', false);

    $this->get(route('privacy'))
        ->assertOk()
        ->assertSee('Privacy Policy');

    $this->get(route('terms'))
        ->assertOk()
        ->assertSee('Terms of Service');
});
