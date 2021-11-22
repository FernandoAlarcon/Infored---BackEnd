<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccionesPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acciones_permisos', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('permiso')->nullable(true)->unsigned(); 
            $table->foreign('permiso')
            ->references('id')
            ->on('roles_permisos_modulos')
            ->onDelete('cascade');

            $table->bigInteger('accion')->nullable(true)->unsigned(); 
            $table->foreign('accion')
            ->references('id')
            ->on('acciones')
            ->onDelete('cascade');

            $table->enum('estado', ['Activado', 'Desactivado'])->default('Activado');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acciones_permisos');
    }
}
