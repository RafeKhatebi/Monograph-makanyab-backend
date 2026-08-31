<?php

use App\Enums\SuggestionStatus;
use App\Models\ContactMessage;
use App\Models\Place;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard reports counts from database', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Place::factory()->create(['is_active' => true]);
    Place::factory()->create(['is_active' => false]);
    Service::factory()->create(['is_active' => true]);
    Service::factory()->create(['is_active' => false]);
    Post::factory()->create(['is_published' => true, 'published_at' => now()]);
    Post::factory()->unpublished()->create();
    ContactMessage::factory()->create(['read_at' => null, 'archived_at' => null]);
    ContactMessage::factory()->create(['archived_at' => now()]);
    PlaceSuggestion::factory()->create(['suggestion_status' => SuggestionStatus::Pending]);
    ServiceSuggestion::factory()->create(['suggestion_status' => SuggestionStatus::Pending]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Total Services')
        ->assertSee('1 Active / 1 Inactive')
        ->assertSee('Posts')
        ->assertSee('1 Published / 1 Draft')
        ->assertSee('Contact Messages')
        ->assertSee('Pending Suggestions')
        ->assertSee('1 Places / 1 Services');
});
