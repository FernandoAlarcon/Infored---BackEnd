<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examen_estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('examen_id')->references('id')->on('examenes')->onUpdate('restrict')->onDelete('cascade');
            $table->string('descripcion')->nullable(false);
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
        Schema::dropIfExists('examen_estados');
    }
}
