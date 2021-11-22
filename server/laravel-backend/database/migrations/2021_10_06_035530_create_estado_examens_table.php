<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoExamensTable extends Migration
{

    public function up()
    {
        Schema::create('estado_examen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50)->unique()->nullable(false);
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
        Schema::dropIfExists('estado_examen');
    }
}
