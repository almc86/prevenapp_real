<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            // Empresa
            $table->string('rut_empresa')->unique();
            $table->string('nombre_empresa');
            $table->string('correo_empresa')->nullable();
            $table->string('telefono')->nullable();

            // Representante Legal
            $table->string('rut_representante');
            $table->string('nombre_representante');
            $table->string('correo_representante')->nullable();

            // Ubicación
            $table->string('region');
            $table->string('comuna');
            $table->string('direccion');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
