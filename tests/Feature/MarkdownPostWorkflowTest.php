<?php

use App\Models\Post;
use App\Models\User;

test('verified users can preview markdown with the published renderer', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('add.markdown-preview'), [
            'content' => "## Community guide\n\n**Useful** details.\n\n| Place | City |\n| --- | --- |\n| Museum | Kabul |",
        ])
        ->assertOk()
        ->assertJsonPath('html', fn (string $html) => str_contains($html, '<h2>Community guide</h2>')
            && str_contains($html, '<strong>Useful</strong>')
            && str_contains($html, '<table>'));
});

test('markdown preview and published posts strip unsafe content', function () {
    $user = User::factory()->create();
    $markdown = "## Safe heading\n\n<script>alert('unsafe')</script>\n\n[unsafe link](javascript:alert('unsafe'))";

    $this->actingAs($user)
        ->postJson(route('add.markdown-preview'), ['content' => $markdown])
        ->assertOk()
        ->assertJsonMissing(['html' => '<script>'])
        ->assertJsonPath('html', fn (string $html) => ! str_contains($html, '<script')
            && ! str_contains($html, 'javascript:')
            && str_contains($html, '<h2>Safe heading</h2>'));

    $post = Post::factory()->create([
        'slug' => 'safe-markdown-post',
        'content' => $markdown,
    ]);

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('<h2>Safe heading</h2>', false)
        ->assertDontSee("<script>alert('unsafe')</script>", false)
        ->assertDontSee('javascript:', false);
});

test('post submissions preserve markdown source and show the shared editor', function () {
    $user = User::factory()->create();
    $markdown = "## A local guide\n\n- First place\n- Second place\n\nThis draft keeps its Markdown source.";

    $this->actingAs($user)
        ->get(route('add.create', ['type' => 'post']))
        ->assertOk()
        ->assertSee('data-markdown-editor', false)
        ->assertSee(route('add.markdown-preview'), false);

    $this->actingAs($user)
        ->post(route('add.store'), [
            'type' => 'post',
            'submit_action' => 'draft',
            'title' => 'Markdown source post',
            'excerpt' => 'A concise local guide.',
            'content' => $markdown,
        ])
        ->assertSessionHasNoErrors();

    expect(Post::where('title', 'Markdown source post')->value('content'))->toBe($markdown);

    $admin = User::factory()->admin()->create();
    $post = Post::where('title', 'Markdown source post')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.posts.edit', $post))
        ->assertOk()
        ->assertSee('data-markdown-editor', false)
        ->assertSee($markdown);
});

test('legacy plain text post line breaks remain visible', function () {
    $post = Post::factory()->create([
        'slug' => 'legacy-plain-post',
        'content' => "First legacy line\nSecond legacy line",
    ]);

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee("First legacy line<br>\nSecond legacy line", false);
});
