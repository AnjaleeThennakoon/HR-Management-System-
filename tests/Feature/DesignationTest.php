<?php

use App\Models\Designation;
use App\Models\User;

beforeEach(function(){
    $this->user = User::factory()->create();
});

test('create designation',function(){
    Designation::factory()->count(10)->create();
    $response = $this->actingAs($this->user)->get('/departments');
    $response->assertStatus(200);
});


