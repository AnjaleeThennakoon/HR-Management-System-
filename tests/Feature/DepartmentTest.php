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
    $departmentdata = ['name' => 'HR'];

    $response = $this->actingAs($this->user)->post('/departments', $departmentdata);

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
    Department::factory()->create(['name' => 'Engineering']);

    $response = $this->actingAs($this->user)->get('/departments?search=Finance');

    $response->assertOk()
        ->assertSee('Finance')
        ->assertDontSee('Engineering');
});

test('department search with wrong name', function () {
    Department::factory()->create(['name' => 'Finance']);

    $response = $this->actingAs($this->user)->get('/departments?search=Hdhudijoej9wb');

    $response->assertViewHas('departments', function ($departments): bool {
        return $departments->isEmpty();
    });
});

test('departments can be paginated', function () {
    Department::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/departments?per_page=5');

    $response->assertViewHas('departments', function ($departments): bool {
        return $departments->count() === 5 && $departments->total() === 10;
    });
});
