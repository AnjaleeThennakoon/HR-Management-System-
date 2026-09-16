<?php

use App\Models\Department;

test('department list page loads successfully', function () {
    Department::factory()->count(4)->create();

    $response = $this->get('/departments');
    $response->assertStatus(200);
});

test('a department can be created', function () {
    $data = ['name' => 'HR'];

    $response = $this->post('/departments', $data);

    $response->assertStatus(201);
    $this->assertDatabaseCount('departments', 1);
    $this->assertDatabaseHas('departments', [
        'name' => 'HR',
    ]);
});

test('a department can be updated', function () {
    $department = Department::factory()->create(['name' => 'HR']);

    $response = $this->put("/departments/{$department->id}", [
        'name' => 'Human Resources',
    ]);

    $response->assertStatus(500);
    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => 'HR',
    ]);
});

test('a department can be deleted', function () {
    $department = Department::factory()->create(['name' => 'HR']);

    $this->assertDatabaseHas('departments', ['id' => $department->id]);

    $this->delete("/departments/{$department->id}");

    $this->assertDatabaseMissing('departments', [
        'id' => $department->id,
    ]);
    $this->assertDatabaseCount('departments', 0);
});

test('departments can be searched' , function () {
    Department::factory()->count(10)->create();

    $response = $this->getJson('/departments?search=Finance');

    $this->assertCount(1, $response->json());

    $this->getJson('/departments?search=HR')->assertStatus(200);

});


test('department search wrong name', function () {
    department::factory()->count(10)->create();

    $response = $this->getJson('/departments?search=Hdhudijoej9wb');


    $response   ->assertStatus(200)
                ->assertJsonCount(0,'data');
});

test('departments can be paginated', function () {
    Department::factory()->count(10)->create();

    $response = $this->getJson('/departments?per_page=2');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('per_page',2);
});
