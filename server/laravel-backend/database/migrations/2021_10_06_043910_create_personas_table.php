<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombres', 120)->nullable(false);
            $table->string('apellidos', 120)->nullable(false);
            $table->foreignId('tipo_documento_id')->references('id')->on('tipo_documento')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('dni')->unique()->nullable(false);
            $table->date('fecha_nacimiento')->nullable()->default('1986-07-13');
            $table->string('telefono', 20);
            $table->string('correo')->unique()->nullable();
            $table->string('direccion');
            $table->foreignId('ciudad_id')->references('id')->on('ciudades')->onDelete('restrict')->onUpdate('cascade');
            $table->string('tipo_sangre', 20);
            $table->text('descripcion', 1200);
            $table->string('fotografia');
            $table->string('firma');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(true);
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_spanish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
