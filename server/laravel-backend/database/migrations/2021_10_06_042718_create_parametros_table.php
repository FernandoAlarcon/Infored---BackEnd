<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_sitio', 80)->nullable(false);
            $table->string('descripcion_sitio')->nullable(true);
            $table->string('logo_sitio', 120)->nullable(true);
            $table->string('color_sitio', 20)->nullable(true);
            $table->bigInteger('nro_dias_diagnostico')->default(8);
            $table->string('correo_sitio', 150);
            $table->string('telefonos_sitio', 150)->nullable(true);
            $table->string('sitio_web_sitio', 150);
            $table->string('direccion_sitio');
            $table->string('coordenadas_sitio');
            $table->string('facebook_sitio', 120)->nullable(true);
            $table->string('instagram_sitio', 120)->nullable(true);
            $table->string('youtube_sitio', 120)->nullable(true);
            $table->string('twitter_sitio', 120)->nullable(true);
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
        Schema::dropIfExists('parametros');
    }
}
