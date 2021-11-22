<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoExamensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_examen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 120)->unique()->nullable(false);
            $table->string('descripcion')->nullable(false);
            $table->bigInteger('mes_eliminacion_adjuntos')->default(12);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(true);
            $table->charset = 'utf8mb4';
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
        Schema::dropIfExists('tipo_examen');
    }
}
