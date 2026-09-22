<?php

use App\Models\Designation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {$this->user = User::factory()->create();});

test('designation list page loads successfully', function () {
    Designation::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/designations');

    $response->assertStatus(200);
    $response->assertViewHas('designations', function ($designations) {return count($designations) == 10;});
});


test('a designation can be created', function () {
    $designation = ['name' => 'Software Engineer', 'level' => 8,];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $this->assertDatabaseHas('designations', ['name' => 'Software Engineer', 'level' => 8,]);
});

test('a designation can be updated', function () {
    $designation = Designation::factory()->create(['name' => 'Software Engineer',]);

    $response = $this->actingAs($this->user)->put("/designations/{$designation->id}",
        ['name' => 'Senior Software Engineer', 'level' => 8,]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('designations', ['id' => $designation->id, 'name' => 'Senior Software Engineer',]);
});

test('a designation can be deleted', function () {
    $designation = Designation::factory()->create(['name' => 'Software Engineer',]);

    $response = $this->actingAs($this->user)->delete("/designations/{$designation->id}");

    $response->assertStatus(302);
    $this->assertDatabaseMissing('designations', ['id' => $designation->id,]);
});


test('designation name cannot be duplicated', function () {
    $designation = Designation::factory()->create(['name' => 'Software Engineer',]);

    $response = $this->actingAs($this->user)
        ->post('/designations', ['name' => $designation->name,]);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('designations', 1);
});


test('designation name cannot be duplicated.', function () {
    $existingDesignation = Designation::factory()->create(['name' => 'Software Engineer',]);
    $this->assertDatabaseHas('designations', ['name' => $existingDesignation->name,]);

    $response = $this->actingAs($this->user)->post('/designations', ['name' => $existingDesignation->name,]);

    $response->assertSessionHasErrors('name');

    $this->assertDatabaseCount('designations', 1);
});

test('designation level can be set manually', function () {
    $response = $this->actingAs($this->user)
        ->post('/designations', ['name' => 'Software Engineer', 'level' => 5,]);

    $response->assertRedirect('/designations');

    $this->assertDatabaseHas('designations', ['name' => 'Software Engineer', 'level' => 5,]);
});



test('designation levels are set correctly regardless of creation order', function () {
    Designation::factory()->create(['name' => 'Software Engineer']);
    Designation::factory()->create(['name' => 'Junior Software Engineer']);
    Designation::factory()->create(['name' => 'Senior Software Engineer']);

    $response = $this->actingAs($this->user)->get('/designations');

    $response->assertOk();
    $this->assertDatabaseHas('designations', ['name' => 'Senior Software Engineer', 'level' => 7, ]);
    $this->assertDatabaseHas('designations', ['name' => 'Software Engineer', 'level' => 8, ]);
    $this->assertDatabaseHas('designations', ['name' => 'Junior Software Engineer', 'level' => 9, ]);
});





