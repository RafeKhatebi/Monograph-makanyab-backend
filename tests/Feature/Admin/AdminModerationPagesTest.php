<?php

use App\Enums\SuggestionStatus;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\ServiceSuggestion;
use App\Models\User;

test('admin status pages keep place service and post submissions separate', function () {
    $admin = User::factory()->admin()->create();

    PlaceSuggestion::factory()->create(['name' => 'Pending Place Example', 'suggestion_status' => SuggestionStatus::Pending]);
    PlaceSuggestion::factory()->create(['name' => 'Approved Place Example', 'suggestion_status' => SuggestionStatus::Approved]);
    ServiceSuggestion::factory()->create(['name' => 'Pending Service Example', 'suggestion_status' => SuggestionStatus::Pending]);
    Post::factory()->unpublished()->create(['title' => 'Pending Post Example', 'submission_status' => SuggestionStatus::UnderReview]);

    $this->actingAs($admin)
        ->get(route('admin.places.pending'))
        ->assertOk()
        ->assertSeeText('Pending Place Example')
        ->assertDontSeeText('Approved Place Example')
        ->assertDontSeeText('Pending Service Example')
        ->assertDontSeeText('Pending Post Example');

    $this->actingAs($admin)
        ->get(route('admin.services.pending'))
        ->assertOk()
        ->assertSeeText('Pending Service Example')
        ->assertDontSeeText('Pending Place Example')
        ->assertDontSeeText('Pending Post Example');

    $this->actingAs($admin)
        ->get(route('admin.posts.pending'))
        ->assertOk()
        ->assertSeeText('Pending Post Example')
        ->assertDontSeeText('Pending Place Example')
        ->assertDontSeeText('Pending Service Example');
});

test('admin post approval and rejection move posts to their corresponding pages', function () {
    $admin = User::factory()->admin()->create();
    $approvedPost = Post::factory()->unpublished()->create([
        'title' => 'Post To Approve',
        'submission_status' => SuggestionStatus::UnderReview,
    ]);
    $rejectedPost = Post::factory()->unpublished()->create([
        'title' => 'Post To Reject',
        'submission_status' => SuggestionStatus::UnderReview,
    ]);

    $this->actingAs($admin)->post(route('admin.posts.approve', $approvedPost))->assertRedirect();
    $this->actingAs($admin)->post(route('admin.posts.reject', $rejectedPost), [
        'admin_note' => 'Please add a source.',
    ])->assertRedirect();

    $this->actingAs($admin)
        ->get(route('admin.posts.pending'))
        ->assertOk()
        ->assertDontSeeText('Post To Approve')
        ->assertDontSeeText('Post To Reject');

    $this->actingAs($admin)
        ->get(route('admin.posts.approved'))
        ->assertOk()
        ->assertSeeText('Post To Approve')
        ->assertDontSeeText('Post To Reject');

    $this->actingAs($admin)
        ->get(route('admin.posts.rejected'))
        ->assertOk()
        ->assertSeeText('Post To Reject')
        ->assertSeeText('Please add a source.')
        ->assertDontSeeText('Post To Approve');
});
