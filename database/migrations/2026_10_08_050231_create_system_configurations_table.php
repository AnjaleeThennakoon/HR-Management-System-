<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key')->unique();
            $table->json('value');
        });
        DB::table('system_configurations')->insert([
            'key' => 'leave',
            'value' => json_encode([
                'annual' => 12,
                'medical' => 12,
                'casual' => 6,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
