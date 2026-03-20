<?php

use App\Models\Ticket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows an authenticated user to create a ticket', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/tickets', [
        'title' => 'Login problem',
        'description' => 'I cannot log into my account after resetting my password.',
        'priority' => 'high',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'Login problem')
        ->assertJsonPath('data.status', 'open')
        ->assertJsonPath('data.priority', 'high')
        ->assertJsonPath('data.user.id', $user->id);

    $this->assertDatabaseHas('tickets', [
        'user_id' => $user->id,
        'title' => 'Login problem',
        'status' => 'open',
        'priority' => 'high',
    ]);
});

it('rejects ticket creation for guests', function () {
    $response = $this->postJson('/api/tickets', [
        'title' => 'Unauthorized',
        'description' => 'This should fail because no token is provided.',
    ]);

    $response->assertUnauthorized();
});

it('validates required fields when creating a ticket', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/tickets', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'description']);
});

it('lists only the authenticated user tickets', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Ticket::factory()->count(2)->create(['user_id' => $user->id]);
    Ticket::factory()->count(3)->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/tickets');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('shows a ticket owned by the authenticated user', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/tickets/{$ticket->id}");

    $response->assertOk()
        ->assertJsonPath('data.id', $ticket->id);
});

it('forbids viewing another users ticket', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ticket = Ticket::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/tickets/{$ticket->id}");

    $response->assertForbidden();
});

it('updates a ticket owned by the authenticated user', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'user_id' => $user->id,
        'status' => 'open',
        'priority' => 'medium',
    ]);

    Sanctum::actingAs($user);

    $response = $this->patchJson("/api/tickets/{$ticket->id}", [
        'status' => 'in_progress',
        'priority' => 'high',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.status', 'in_progress')
        ->assertJsonPath('data.priority', 'high');

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => 'in_progress',
        'priority' => 'high',
    ]);
});

it('deletes a ticket owned by the authenticated user', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson("/api/tickets/{$ticket->id}");

    $response->assertOk()
        ->assertJsonPath('message', 'Ticket deleted successfully.');

    $this->assertDatabaseMissing('tickets', [
        'id' => $ticket->id,
    ]);
});
