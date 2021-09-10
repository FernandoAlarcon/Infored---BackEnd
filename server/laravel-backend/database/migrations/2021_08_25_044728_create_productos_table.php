<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->char('nombre', 100);
            $table->string('descripcion', 500);
            $table->char('cantidad', 100);
            $table->char('precio', 100);
            $table->bigInteger('categoria')->unsigned(); 
            
            $table->foreign('categoria')
            ->references('id')
            ->on('categorias')
            ->onDelete('cascade');
            
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
        Schema::dropIfExists('productos');
    }
}
