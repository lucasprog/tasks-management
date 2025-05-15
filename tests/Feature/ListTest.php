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
        ->post('/tasks', [
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
        ->get("/lists/{$this->task['data']['id']}");
    
    $response
         ->assertStatus(200);
    
});

test('creating item list for task, and verify status (must be 201) and data response', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
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

test('try to create item list for task without task id, and verify status (must be 404)', function () {
    
    $response = $this
        ->actingAs($this->user)
        ->post("/lists", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $response
        ->assertStatus(404);
    
});

test('try to create item list for task with task id that not belongs to user, and verify status (must be 404)', function () {
    
    $response = $this
        ->actingAs($this->user2)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $response
        ->assertStatus(404);
    
});

test('updating item list for task, and verify status (must be 200) and data response', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->put("/lists/{$this->task['data']['id']}", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
            '_token' => $this->csrf_token
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
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->put("/lists", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
            '_token' => $this->csrf_token
        ]);
    
    $response
         ->assertStatus(404);
    
});

test('try to update item list for task that task id not belongs to user and verify status (must be 404)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user2)
        ->put("/lists/{$this->task['data']['id']}", [
            'id' => $listCreated['data']['id'],
            'item' => 'Item 001',
            'done' => true,
            '_token' => $this->csrf_token
        ]);
    
    $response
         ->assertStatus(404);
    
});

test('try to delete item list of task and verify status (must be 200)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user)
        ->delete("/lists/{$this->task['data']['id']}/{$listCreated['data']['id']}");
    
    $response
         ->assertStatus(200)
         ->assertJson([
            'message' => 'List deleted with success!'
         ]);
    
});

test('try to delete item list of task that task id not belongs to user and verify status (must be 404)', function () {
    
    $responseCreation = $this
        ->actingAs($this->user)
        ->post("/lists/{$this->task['data']['id']}", [
            'item' => 'Item 1',
            'done' => false,
            '_token' => $this->csrf_token
        ]);
    
    $listCreated = $responseCreation->json();

    $response = $this
        ->actingAs($this->user2)
        ->delete("/lists/{$this->task['data']['id']}/{$listCreated['data']['id']}");
    
    $response
         ->assertStatus(404)
         ->assertJson([
            'message' => 'List Not Found'
         ]);
    
});

