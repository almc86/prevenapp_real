<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargo_categoria_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('configuracion_id');
            $table->unsignedBigInteger('cargo_id');
            $table->unsignedBigInteger('categoria_id');
            $table->timestamps();

            $table->unique(['configuracion_id', 'cargo_id', 'categoria_id'], 'cargo_cat_config_unique');

            $table->foreign('configuracion_id')->references('id')->on('configuraciones')->onDelete('cascade');
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        });

        // Backfill: agregar registros para categorías que ya tienen documentos asignados a cargos
        $existing = DB::table('configuracion_categoria_documento')
            ->whereNotNull('cargo_id')
            ->select('configuracion_id', 'cargo_id', 'categoria_id')
            ->distinct()
            ->get();

        foreach ($existing as $row) {
            DB::table('cargo_categoria_config')->insertOrIgnore([
                'configuracion_id' => $row->configuracion_id,
                'cargo_id' => $row->cargo_id,
                'categoria_id' => $row->categoria_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_categoria_config');
    }
};
