<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnMatriculaDeQuemPegou extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiais_perdidos', function (Blueprint $table) {
            $table->string('matriculaDeQuemPegou', 20)
                ->nullable(true)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiais_perdidos', function (Blueprint $table) {
            $table->string('matriculaDeQuemPegou', 20)
                ->nullable(false)
                ->change();
        });
    }
}
