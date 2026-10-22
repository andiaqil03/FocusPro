<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;

function makeTask(User $owner, array $overrides = []): Task
{
    return Task::create(array_merge([
        'user_id' => $owner->id,
        'title' => 'Write report',
        'priority' => 'P2',
        'status' => 'pending',
    ], $overrides));
}

test('guests cannot mark a task as completed', function () {
    $task = makeTask(User::factory()->create());

    $this->patch("/tasks/{$task->id}/complete")->assertRedirect('/login');

    expect($task->fresh()->status)->toBe('pending');
});

test('users cannot complete another user\'s task', function () {
    $task = makeTask(User::factory()->create());

    $this->actingAs(User::factory()->create())
        ->patch("/tasks/{$task->id}/complete")
        ->assertForbidden();

    expect($task->fresh()->status)->toBe('pending');
});

test('owners can complete their own task', function () {
    $owner = User::factory()->create();
    $task = makeTask($owner);

    $this->actingAs($owner)
        ->patch("/tasks/{$task->id}/complete")
        ->assertRedirect('/tasks');

    expect($task->fresh()->status)->toBe('completed');
});

test('updating a task cannot reassign it to another user', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $task = makeTask($owner);

    $this->actingAs($owner)->put("/tasks/{$task->id}", [
        'title' => 'Renamed',
        'priority' => 'P1',
        'status' => 'in_progress',
        'user_id' => $other->id,
    ])->assertRedirect('/tasks');

    $task->refresh();
    expect($task->user_id)->toBe($owner->id)
        ->and($task->title)->toBe('Renamed');
});

test('pdf exports require login', function () {
    $this->get('/tasks/export-pdf')->assertRedirect('/login');
    $this->get('/analytics/download-pdf')->assertRedirect('/login');
});

test('tasks can be sorted by priority', function () {
    $owner = User::factory()->create();
    makeTask($owner, ['title' => 'Low', 'priority' => 'P4']);
    makeTask($owner, ['title' => 'Urgent', 'priority' => 'P1']);

    $this->actingAs($owner)
        ->get('/tasks?sort_by=priority')
        ->assertOk()
        ->assertSeeInOrder(['Urgent', 'Low']);
});

test('profile photo must be an image', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch('/profile', [
        'name' => $user->name,
        'email' => $user->email,
        'profile_photo' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
    ])->assertSessionHasErrors('profile_photo');

    expect($user->fresh()->profile_photo_path)->toBeNull();
});
