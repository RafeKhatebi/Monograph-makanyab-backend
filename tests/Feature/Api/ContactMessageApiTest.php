<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('anyone can submit a contact message', function () {
    $data = [
        'name' => 'John Doe',
        'telephone' => '+1234567890',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message.',
    ];

    $this->postJson('/api/contact-messages', $data)
        ->assertCreated()
        ->assertJsonPath('message', 'Contact message received.')
        ->assertJsonPath('data.subject', 'Test Subject')
        ->assertJsonMissing(['message' => 'This is a test message.']);
});

test('contact message requires all fields', function () {
    $this->postJson('/api/contact-messages', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'telephone', 'email', 'subject', 'message']);
});

test('contact message requires valid email', function () {
    $this->postJson('/api/contact-messages', [
        'name' => 'John',
        'telephone' => '+1234567890',
        'email' => 'invalid-email',
        'subject' => 'Test',
        'message' => 'Test message',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('contact message length is limited', function () {
    $this->postJson('/api/contact-messages', [
        'name' => 'John',
        'telephone' => '+1234567890',
        'email' => 'john@example.com',
        'subject' => 'Test',
        'message' => str_repeat('a', 2001),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});
