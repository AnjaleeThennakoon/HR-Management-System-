<?php

use App\Models\Designation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('designation list page loads successfully', function () {
    Designation::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/designations');

    $response->assertStatus(200);
    $response->assertSee('name="_method" id="designationMethod" value="PUT" disabled', false);
    $response->assertViewHas('designations', function ($designations) {
        return count($designations) == 10;
    });
});

test('designation can be created without a upper level', function () {
    $designation = ['name' => 'Software Engineer', 'upper_level' => null];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['name' => 'Software Engineer', 'upper_level' => null]);
});

test('a designation is added to the end of the list when only the name is given', function () {
    Designation::factory()->create(['name' => 'Software Engineering', 'level' => 1]);
    Designation::factory()->create(['name' => 'Senior software engineer', 'level' => 2]);
    $designation = ['name' => 'Intern Software Engineer'];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['name' => 'Intern Software Engineer', 'level' => 3]);
});

test('a designation can be created when upper level is given', function () {
    $upperLevelDesignation = Designation::factory()->create(['name' => 'Software Engineer']);
    $designation = ['name' => 'Intern Software Engineer', 'upper_level' => $upperLevelDesignation->id];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations',
        ['name' => 'Intern Software Engineer', 'upper_level' => $upperLevelDesignation->id]);
});

test('a designation name can be updated', function () {
    $designation = Designation::factory()->create(
        ['name' => 'Intern Software Engineer', 'level' => 2]);
    $designationData = ['name' => 'Software Engineer'];

    $response = $this->actingAs($this->user)->put("/designations/{$designation->id}", $designationData);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', [
        'id' => $designation->id,
        'name' => 'Software Engineer',
        'level' => 2,
        'upper_level' => null,
    ]);
});

test('a designation upper level can be updated', function () {
    $senior = Designation::factory()->create(['name' => 'Senior Software Engineer', 'upper_level' => null]);
    $software = Designation::factory()->create(['name' => 'Software Engineer', 'upper_level' => $senior->id]);
    $junior = Designation::factory()->create(['name' => 'Junior Software Engineer', 'upper_level' => $software->id]);
    $intern = Designation::factory()->create(['name' => 'Intern Software Engineer', 'upper_level' => $junior->id]);

    $designationData = ['name' => 'Junior Software Engineer', 'upper_level' => $senior->id];

    $response = $this->actingAs($this->user)->put("/designations/{$junior->id}", $designationData);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['id' => $senior->id,
        'name' => 'Senior Software Engineer', 'upper_level' => null, ]);
    $this->assertDatabaseHas('designations', ['id' => $software->id,
        'name' => 'Software Engineer', 'upper_level' => $junior->id, ]);
    $this->assertDatabaseHas('designations', ['id' => $junior->id,
        'name' => 'Junior Software Engineer', 'upper_level' => $senior->id, ]);
    $this->assertDatabaseHas('designations', ['id' => $intern->id,
        'name' => 'Intern Software Engineer', 'upper_level' => $software->id, ]);
});

test('a designation can be deleted', function () {
    $designation = Designation::factory()->create(['name' => 'Software Engineer', 'level' => 1]);

    $this->actingAs($this->user)
        ->delete("/designations/{$designation->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('designations', ['id' => $designation->id]);
});

test('designation name cannot be duplicated.', function () {
    $existingDesignation = Designation::factory()->create(['name' => 'Software Engineer']);
    $newDesignation = ['name' => 'Software Engineer', 'upper_level' => null];

    $response = $this->actingAs($this->user)->post('/designations', $newDesignation);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('designations', 1);
});

test('level is updated when a designation upper level is changed', function () {
    $senior = Designation::factory()->create(
        ['name' => 'Senior Software Engineer', 'upper_level' => null, 'level' => 1]);
    $software = Designation::factory()->create(
        ['name' => 'Software Engineer', 'upper_level' => $senior->id, 'level' => 2]);
    $junior = Designation::factory()->create(
        ['name' => 'Junior Software Engineer', 'upper_level' => $software->id, 'level' => 3]);
    $designationData =
        ['name' => 'Junior Software Engineer', 'upper_level' => $senior->id];

    $response = $this->actingAs($this->user)->put("/designations/{$junior->id}", $designationData);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations',
        ['id' => $junior->id, 'name' => 'Junior Software Engineer', 'upper_level' => $senior->id, 'level' => 2]);
});

test('a designation without upper level is added to the end of the list', function () {
    Designation::query()->delete();
    $ceo = Designation::factory()->create(['name' => 'Chief Executive Officer', 'upper_level' => null, 'level' => 1]);
    $cfo = Designation::factory()->create(['name' => 'Chief Financial Officer', 'upper_level' => $ceo->id, 'level' => 2]);
    $db = Designation::factory()->create(['name' => 'Database Administrator', 'upper_level' => null, 'level' => 3]);
    $Network = Designation::factory()->create(['name' => 'Network Engineer', 'upper_level' => null, 'level' => 4]);

    $response = $this->actingAs($this->user)->post('/designations', [
        'name' => 'Intern Software Engineer', 'upper_level' => $cfo->id, ]);
    $response->assertStatus(302);
    $response->assertRedirect('/designations');

    $this->assertDatabaseHas('designations',
        ['id' => $ceo->id, 'name' => 'Chief Executive Officer', 'upper_level' => null, 'level' => 1]);
    $this->assertDatabaseHas('designations',
        ['id' => $cfo->id, 'name' => 'Chief Financial Officer', 'upper_level' => $ceo->id, 'level' => 2]);
    $intern = Designation::where('name', 'Intern Software Engineer')->first();
    $this->assertDatabaseHas('designations',
        ['id' => $intern->id, 'name' => 'Intern Software Engineer', 'upper_level' => $cfo->id, 'level' => 3, ]);
    $this->assertDatabaseHas('designations',
        ['id' => $db->id, 'name' => 'Database Administrator', 'upper_level' => null, 'level' => 4]);
    $this->assertDatabaseHas('designations',
        ['id' => $Network->id, 'name' => 'Network Engineer', 'upper_level' => null, 'level' => 5]);
});

test('designations with the same upper level have the same level', function () {
    Designation::query()->delete();
    $ceo = Designation::factory()->create(['name' => 'Chief Executive Officer', 'upper_level' => null, 'level' => 1,]);
    $this->actingAs($this->user)->post('/designations',
        ['name' => 'Chief Financial Officer', 'upper_level' => $ceo->id,]);
    $this->actingAs($this->user)->post('/designations',
        ['name' => 'Chief Operating Officer', 'upper_level' => $ceo->id,]);
    $this->actingAs($this->user)->post('/designations',
        ['name' => 'Chief Information Officer', 'upper_level' => $ceo->id,]);

    $this->assertDatabaseHas('designations',
        ['name' => 'Chief Executive Officer', 'upper_level' => null, 'level' => 1,]);
    $this->assertDatabaseHas('designations',
        ['name' => 'Chief Financial Officer', 'upper_level' => $ceo->id, 'level' => 2,]);
    $this->assertDatabaseHas('designations', [
        'name' => 'Chief Operating Officer', 'upper_level' => $ceo->id, 'level' => 2,]);
    $this->assertDatabaseHas('designations', [
        'name' => 'Chief Information Officer', 'upper_level' => $ceo->id, 'level' => 2,]);

    $cLevels = Designation::where('upper_level', $ceo->id)->pluck('level')->unique()->toArray();
    expect($cLevels)->toBe([2]);
});
