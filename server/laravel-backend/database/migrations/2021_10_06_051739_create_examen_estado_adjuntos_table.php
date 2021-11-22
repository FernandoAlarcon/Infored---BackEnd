<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenEstadoAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examen_estado_adjuntos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_examen_estados')->references('id')->on('examen_estados')->onUpdate('restrict')->onDelete('cascade');
            $table->string('adjunto')->nullable(false);
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
        Schema::dropIfExists('examen_estado_adjuntos');
    }
}
