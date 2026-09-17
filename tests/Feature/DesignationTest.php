<?php

use App\Models\Department;
use App\Models\Designation;
use App\Models\User;

beforeEach(function(){
    $this->user = User::factory()->create();
});

test('designation list page loads successfully', function () {
    Designation::factory()->count(10)->create();

    $response = $this->actingAs($this->user)
        ->get('/designations');

    $response->assertStatus(200);
});
test('a designation can be created', closure: function () {
    $response = $this->actingAs($this->user)->post('/designations', [
            'name' => 'Software Engineer',
        ]);

    $response->assertStatus(302);

    $this->assertDatabaseHas('designations', [
        'name' => 'Software Engineer',
    ]);
});




