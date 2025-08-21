<?php

// database/migrations/2025_08_09_000000_create_regiones_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('regiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('comunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regiones')->cascadeOnDelete();
            $table->string('nombre');
            $table->timestamps();

            $table->unique(['region_id','nombre']); // misma comuna no repetida dentro de una región
        });
    }
    public function down(): void {
        Schema::dropIfExists('comunas');
        Schema::dropIfExists('regiones');
    }
};

