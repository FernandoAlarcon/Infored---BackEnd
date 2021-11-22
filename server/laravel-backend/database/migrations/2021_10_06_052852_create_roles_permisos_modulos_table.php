<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPermisosModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_permisos_modulos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rol_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('modulo_id')->references('id')->on('modulos')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(true);
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_spanish_ci';
            $table->comment = 'Permisos de roles sobre modulos';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_permisos_modulos');
    }
}
