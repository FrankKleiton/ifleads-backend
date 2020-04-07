<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaDeMateriaisPerdidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiais_perdidos', function (Blueprint $table) {
            $table->boolean('devolvido')->default(false);
            $table->string('matriculaDeQuemEntregou', 20)->nullable(false);
            $table->string('matriculaDeQuemPegou', 20)->nullable(false);
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')
                ->references('id')
                ->on('materiais')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materiais_perdidos');
    }
}
