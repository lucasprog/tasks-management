<?php

use App\Models\User;
use Carbon\Carbon;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
 
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate');
});

test('try to get tasks without permission and verify if it redirect (status 302) denying access', function () {
    $response = $this->get('/tasks/get');
    $response
    ->assertStatus(302);
});


test('creating and getting task and verify status (must be 200) and data response', function () {
    
    Session::start();
    
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/tasks', [
            'title' => 'Create API Rest',
            'description' => 'Create API Rest for main project',
            'warn_me' => true,
            'starts_at' => now()->addDay(1)->toDateTimeString(),
            '_token' => csrf_token()
        ]);
        
    $responseGot = $this
        ->actingAs($user)
        ->get('/tasks/get');

    $responseGot
         ->assertStatus(200)
         ->assertJson([
             'data' => [
                [
                    'id' => 1,
                    'title' => 'Create API Rest',
                    'description' => 'Create API Rest for main project',
                    'warn_me' => true,
                    'starts_at' => now()->addDay(1)->toDateTimeString()
                ],
            ]
         ]);
    
});

test('creating and updating task and verify status (must be 201 for create and 200 for update), data, and message response', function () {
    
    Session::start();

    $user = User::factory()->create();

     $responseCreated = $this
         ->actingAs($user)
         ->post('/tasks', [
             'title' => 'Create API Rest',
             'description' => 'Create API Rest for main project',
             'warn_me' => true,
             'starts_at' => now()->addDay(1)->toDateTimeString(),
             '_token' => csrf_token()
         ]);

    $responseCreated
         ->assertStatus(201)
         ->assertJson([
             'data' => [
                 'id' => 1,
                 'title' => 'Create API Rest',
                 'description' => 'Create API Rest for main project',
                 'warn_me' => true,
                 'starts_at' => now()->addDay(1)->toDateTimeString()
             ],
             'message' => 'Task created with success!'
         ]);

    $responseUpdated = $this
        ->actingAs($user)
        ->put('/tasks', [
            'id' => 1,
            'title' => 'Update API Rest',
            'description' => 'Update API Rest for main project',
            'warn_me' => true,
            'starts_at' => "2025-05-16 12:00:00",
            '_token' => csrf_token()
        ]);

    $responseUpdated
        ->assertStatus(200)
        ->assertJson([
           'message' => 'Task updated with success!'
        ]);

    $responseUpdatedVerify = $this->actingAs($user)
        ->get('/tasks/get?id=1');
    
    $responseUpdatedVerify
        ->assertStatus(200)
        ->assertJson([
            'id' => 1,
            'title' => 'Update API Rest',
            'description' => 'Update API Rest for main project',
            'warn_me' => true,
            'starts_at' => "2025-05-16 12:00:00"
        ]); 
    
});

test('try to update task with wrong ID and verify status (must be 500) and message response', function () {

    Session::start();

    $user = User::factory()->create();

    $responseUpdated = $this
        ->actingAs($user)
        ->put('/tasks', [
            'id' => 1,
            'title' => 'Update Without Permission API Rest',
            'description' => 'Update Without Permission API Rest for main project',
            'warn_me' => true,
            'starts_at' => now()->addDay(2)->toDateTimeString(),
            '_token' => csrf_token()
        ]);

    $responseUpdated
        ->assertStatus(500)
        ->assertJson([
           'message' => 'Failed to update, Task not found'
        ]);
    
});

test('deleting tasks and verify status (must be 200) and message response', function () {

    $user = User::factory()->create();

     $responseCreated = $this
         ->actingAs($user)
         ->post('/tasks', [
             'title' => 'Create API Rest',
             'description' => 'Create API Rest for main project',
             'warn_me' => true,
             'starts_at' => now()->addDay(1)->toDateTimeString(),
             '_token' => csrf_token()
         ]);

    $responseCreated
         ->assertStatus(201)
         ->assertJson([
             'data' => [
                 'id' => 1,
                 'title' => 'Create API Rest',
                 'description' => 'Create API Rest for main project',
                 'warn_me' => true,
                 'starts_at' => now()->addDay(1)->toDateTimeString()
             ],
             'message' => 'Task created with success!'
         ]);

    $responseUpdated = $this
        ->actingAs($user)
        ->delete('/tasks/1');

    $responseUpdated
        ->assertStatus(200)
        ->assertJson([
           'message' => 'Task deleted with success!'
        ]);
});

test('deleting tasks with wrong ID and verify status (must be 500) and message response', function () {

    $user = User::factory()->create();

    $responseUpdated = $this
        ->actingAs($user)
        ->delete('/tasks/3');

    $responseUpdated
        ->assertStatus(500)
        ->assertJson([
           'message' => 'Failed to delete Task, Task not found!'
        ]);
});
