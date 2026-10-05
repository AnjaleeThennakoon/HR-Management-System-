<?php

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('holiday list page loads successfully', function () {
    Holiday::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/holidays');

    $response->assertStatus(200)
        ->assertsee('name="_method" id="holidayMethod" value="PUT" disabled', false)
        ->assertViewHas('holidays', function ($holidays) {
            return count($holidays) == 10;
        });
});

test('a holiday can be created', function () {
    $holidaydata = [
        'name' => 'New Year',
        'date' => '2024-01-01',
        'type' => 'public',
    ];

    $response = $this->actingAs($this->user)->post('/holidays', $holidaydata);

    $response->assertStatus(302);
    $response->assertRedirect('/holidays');
    $this->assertDatabaseCount('holidays', 1);
    $this->assertDatabaseHas('holidays', [
        'name' => 'New Year',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);
});

test('a holiday can  be updated', function () {
    $holiday = Holiday::factory()->create([
        'name' => 'Old Name',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);
    $updatedData = [
        'name' => 'Updated Name',
        'date' => '2024-12-25',
        'type' => 'public',
    ];

    $response = $this->actingAs($this->user)->put("/holidays/{$holiday->id}", $updatedData);

    $response->assertStatus(302);
    $response->assertRedirect('/holidays');
    $this->assertDatabaseHas('holidays', [
        'id' => $holiday->id,
        'name' => 'Updated Name',
        'date' => '2024-12-25',
        'type' => 'public',
    ]);

});

test('a holiday can  be deleted', function () {
    $holiday = Holiday::factory()->create([
        'name' => 'New Year',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);

    $response = $this->actingAs($this->user)->delete("/holidays/{$holiday->id}");

    $response->assertStatus(302);
    $response->assertRedirect('/holidays');
    $this->assertDatabaseMissing('holidays', [
        'id' => $holiday->id,
    ]);
});

test('a holiday type must be public or special', function () {
    Holiday::factory()->create([
        'name' => 'Public Holiday',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);
    Holiday::factory()->create([
        'name' => 'Special Holiday',
        'date' => '2024-01-01',
        'type' => 'special',
    ]);
    $holiday3 = Holiday::factory()->make([
        'name' => 'Invalid Type Holiday',
        'date' => '2024-01-01',
        'type' => 'invalid_type',
    ]);

    $response = $this->actingAs($this->user)->post('/holidays', $holiday3->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('type');
    $this->assertDatabaseCount('holidays', 2);
});

test('a holiday name need to be unique', function () {
    $holiday = Holiday::factory()->create([
        'name' => 'New Year Holiday',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);
    $newHoliday = Holiday::factory()->make([
        'name' => 'New Year Holiday',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);

    $response = $this->actingAs($this->user)->post('/holidays', $newHoliday->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('holidays', 1);
});
