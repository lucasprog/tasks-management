<?php

use App\Models\User;
use Carbon\Carbon;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
 
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate');
    Session::start();
    $this->user = User::factory()->create();
    $this->user2 = User::factory()->create();
    $this->csrf_token = csrf_token();
    $response = $this->actingAs($this->user)
        ->post('/api/tasks', [
            'title' => 'Task With List',
            'description' => 'This task has many items, or not',
            'warn_me' => true,
            'starts_at' => now()->addDay(1)->toDateTimeString(),
            '_token' => csrf_token()
        ]);

    $this->task = $response->json();

});

test('getting item list of task, and verify status (must be 200) and data response', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->get("/api/lists/{$this->task['data']['id']}");
    
    $response
         ->assertStatus(200);
    
});

test('creating item list for task, and verify status (must be 201) and data response', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $response
         ->assertStatus(201)
         ->assertJson([
                'data' => [
                    'id' => 1,
                    'item' => 'Item 1',
                    'done' => false,
                ],
                "message" => "List created with success!"
         ]);
    
});

test('creating item list group for task, and verify status (must be 201) and data response', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->post("/api/lists/group/{$this->task['data']['id']}", [
            [
                'item' => 'Item 1',
                'done' => false,
            ],
            [
                'item' => 'Item 2',
                'done' => false,
            ],
            [
                'item' => 'Item 3',
                'done' => false,
            ]
        ]);
    
    $response
         ->assertStatus(201)
         ->assertJson([
                'data' => [
                    [
                        'id' => 1,
                        'item' => 'Item 1',
                        'done' => false,
                    ],
                    [
                        'id' => 2,
                        'item' => 'Item 2',
                        'done' => false,
                    ],
                    [
                        'id' => 3,
                        'item' => 'Item 3',
                        'done' => false,
                    ]
                ],
                "message" => "Group List created with success!"
         ]);
    
});

test('try to create item list for task without task id, and verify status (must be 404)', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->post("/api/lists", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $response
        ->assertStatus(404);
    
});

test('try to create item list for task with task id that not belongs to user, and verify status (must be 404)', function () {
    
    $response = $this
        ->actingAs($this->user2)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $response
        ->assertStatus(404);
    
});

test('updating item list for task, and verify status (must be 200) and data response', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->put("/api/lists/{$this->task['data']['id']}", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
        ]);
    
    $response
         ->assertStatus(200)
         ->assertJson([
                "message" => "List updated with success!"
         ]);
    
});

test('try to update item list for task, without task id and verify status (must be 404)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->put("/api/lists", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
        ]);
    
    $response
         ->assertStatus(404);
    
});

test('try to update item list for task that task id not belongs to user and verify status (must be 404)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user2)
        ->put("/api/lists/{$this->task['data']['id']}", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
        ]);
    
    $response
         ->assertStatus(404);
    
});

test('try to delete item list of task and verify status (must be 200)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->delete("/api/lists/{$this->task['data']['id']}/{$listCreated['data']['id']}");
    
    $response
         ->assertStatus(200)
         ->assertJson([
            'message' => 'List deleted with success!'
         ]);
    
});

test('try to delete item list of task that task id not belongs to user and verify status (must be 404)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/api/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user2)
        ->delete("/api/lists/{$this->task['data']['id']}/{$listCreated['data']['id']}");
    
    $response
         ->assertStatus(404)
         ->assertJson([
            'message' => 'List Not Found'
         ]);
    
});

