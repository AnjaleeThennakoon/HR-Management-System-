<?php


use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('department list page loads successfully', function () {
    Employee ::factory()->count(4)->create();

    $response = $this->actingAs($this->user)->get('/employees');
    $response->assertStatus(200);
});
