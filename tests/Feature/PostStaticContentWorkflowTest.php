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

test('submitted post creation generates unique slugs and leaves drafts unpublished', function () {
    $user = User::factory()->create();
    Post::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);

    $this->actingAs($user)
        ->post(route('add.store'), [
            'type' => 'post',
            'submit_action' => 'draft',
            'title' => 'Duplicate Title',
            'excerpt' => 'Short summary',
            'content' => 'Body text with enough detail for a saved draft post submission in the new user workflow.',
        ])
        ->assertRedirect(route('add.create', ['type' => 'post']));

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
