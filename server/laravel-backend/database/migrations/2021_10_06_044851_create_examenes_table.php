<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('fecha_inicio')->nullable(false);
            $table->dateTime('fecha_fin')->nullable(false);
            $table->foreignId('medico_id')->references('id')->on('personas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('tecnico_id')->references('id')->on('personas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('paciente_id')->references('id')->on('personas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_tipo_examen')->references('id')->on('tipo_examen')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_clinica')->references('id')->on('clinicas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_estado_examen')->references('id')->on('estado_examen')->onDelete('restrict')->onUpdate('cascade')->comment('1=programado 2=confirmado 3=concepto_tecnico 4=diagnosticado_abierto 5=finalizado.');
            $table->text('descripcion', 1200);
            $table->double('costo_examen', 20,4)->default(00.0)->comment('Valor Del Examen.');
            $table->string('pdf_examen')->comment('PDF Generado por examen, no se elimina.');
            $table->dateTime('fecha_cargue_datos_tecnico')->comment('Fecha en que el técnico radiólogo carga los adjunto.');
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
        Schema::dropIfExists('examenes');
    }
}
