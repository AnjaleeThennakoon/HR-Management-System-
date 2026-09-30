<?php


use App\Models\User;
use App\Models\Holiday;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
    $this->user = User::factory()->create();
});

test('holiday list page loads successfully',function(){
    Holiday::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/holidays');

    $response->assertStatus(200)
        ->assertsee('name="_method" id="holidayMethod" value="PUT" disabled', false)
        ->assertViewHas('holidays' , function($holidays) {
            return count($holidays) == 10;
        });
});

test('a holiday can be created', function () {
    $holiday = Holiday::factory()->make([
        'name' => 'New Year',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);

    $response = $this->actingAs($this->user)->post('/holidays', $holiday->toArray());

    $response->assertStatus(302);
    $response->assertRedirect('/holidays');
    $this->assertDatabaseCount('holidays', 1);
    $this->assertDatabaseHas('holidays', [
        'name' => 'New Year',
        'date' => '2024-01-01',
        'type' => 'public',
    ]);
});

test('a holiday can  be updated',function(){
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
