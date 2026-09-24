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

test('designation can be created without a upper level', function () {
    $designation = ['name' => 'Software Engineer', 'upper_level' => null];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['name' => 'Software Engineer','upper_level' => null,]);
});

test('a designation is added to the end of the list when only the name is given',function(){
    Designation::factory()->create(['name'=>'Software Engineering','level'=>1,]);
    Designation::factory()->create(['name'=>'Senior software engineer','level'=>2,]);
    $designation =['name'=>'Intern Software Engineer',];

    $response=$this->actingAs($this->user)->post('/designations', $designation );

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations',['name'=>'Intern Software Engineer','level'=>3,]);
});

test('a designation can be created when upper level is given', function () {
    $upperLevelDesignation = Designation::factory()->create(['name' => 'Software Engineer',]);
    $designation = ['name' => 'Intern Software Engineer', 'upper_level' => $upperLevelDesignation->id,];

    $response = $this->actingAs($this->user)->post('/designations', $designation);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['name' => 'Intern Software Engineer', 'upper_level' => $upperLevelDesignation->id,]);
});

test('a designation name can be updated', function () {
    $designation = Designation::factory()->create(['name' => 'Intern Software Engineer', 'level' => 1,]);
    $designationData= ['name' => 'Software Engineer', 'level' => 1,];

    $response = $this->actingAs($this->user)->put("/designations/{$designation->id}", $designationData);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['id' => $designation->id, 'name' => 'Software Engineer', 'level' => 1,]);
});

test('a designation upper level can be updated', function () {
    $senior = Designation::factory()->create(['name' => 'Senior Software Engineer', 'upper_level' => null,]);
    $software = Designation::factory()->create(['name' => 'Software Engineer', 'upper_level' => $senior->id,]);
    $junior = Designation::factory()->create(['name' => 'Junior Software Engineer', 'upper_level' => $software->id,]);
    $intern = Designation::factory()->create(['name' => 'Intern Software Engineer', 'upper_level' => $junior->id,]);

    $designationData = ['name' => 'Junior Software Engineer', 'upper_level' => $senior->id,];

    $response = $this->actingAs($this->user)->put("/designations/{$junior->id}", $designationData);

    $response->assertStatus(302);
    $response->assertRedirect('/designations');
    $this->assertDatabaseHas('designations', ['id' => $senior->id, 'name' => 'Senior Software Engineer', 'upper_level' => null,]);
    $this->assertDatabaseHas('designations', ['id' => $software->id, 'name' => 'Software Engineer', 'upper_level' => $junior->id,]);
    $this->assertDatabaseHas('designations', ['id' => $junior->id, 'name' => 'Junior Software Engineer', 'upper_level' => $senior->id,]);
    $this->assertDatabaseHas('designations', ['id' => $intern->id, 'name' => 'Intern Software Engineer', 'upper_level' => $software->id,]);
});

test('a designation can be deleted', function () {
    $designation = Designation::factory()->create(['name' => 'Software Engineer', 'level' => 1,]);

    $this->actingAs($this->user)
        ->delete("/designations/{$designation->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('designations', ['id' => $designation->id,]);
});

test('designation name cannot be duplicated.', function () {
    $existingDesignation = Designation::factory()->create(['name' => 'Software Engineer',]);
    $newDesignation =['name' => 'Software Engineer','upper_level' => null,];

    $response = $this->actingAs($this->user)->post('/designations', $newDesignation);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('designations', 1);
});




