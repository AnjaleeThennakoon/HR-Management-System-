<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('department list page loads successfully', function () {
    Department::factory()->count(4)->create();

    $response = $this->actingAs($this->user)->get('/departments');
    $response->assertStatus(200);
});

test('a department can be created', function () {
    $data = ['name' => 'HR'];

    $response = $this->actingAs($this->user)->post('/departments', $data);

    $response->assertStatus(302);
    $response->assertRedirect('/departments');
    $this->assertDatabaseCount('departments', 1);
    $this->assertDatabaseHas('departments', [
        'name' => 'HR',
    ]);
});

test('a department can be updated', function () {
    $department = Department::factory()->create(['name' => 'HR']);

    $response = $this->actingAs($this->user)->put("/departments/{$department->id}", [
        'name' => 'Human Resources',
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => 'Human Resources',
    ]);
});

test('a department can be deleted', function () {
    $department = Department::factory()->create(['name' => 'HR']);

    $this->assertDatabaseHas('departments', ['id' => $department->id]);

    $this->actingAs($this->user)->delete("/departments/{$department->id}");

    $this->assertDatabaseMissing('departments', [
        'id' => $department->id,
    ]);
});

test('departments can be searched', function () {
    Department::factory()->create(['name' => 'Finance']);

    Department::factory()
        ->count(9)
        ->sequence(fn ($sequence) => ['name' => "Department {$sequence->index}"])
        ->create();

    $response = $this->actingAs($this->user)->get('/departments');

    $response->assertOk()
        ->assertSee('Finance');
});

test('department search with wrong name', function () {
    Department::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/departments');

    $response->assertOk()
        ->assertDontSee('Hdhudijoej9wb');
});

test('departments can be paginated', function () {
    Department::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/departments');

    $response->assertOk()
        ->assertSee('Human Resources');
});
