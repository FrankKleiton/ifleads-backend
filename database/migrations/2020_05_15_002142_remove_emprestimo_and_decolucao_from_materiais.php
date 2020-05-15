<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEmprestimoAndDecolucaoFromMateriais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiais', function (Blueprint $table) {
            $table->dropColumn('horarioEmprestimo');
            $table->dropColumn('horarioDevolucao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiais', function (Blueprint $table) {
            $table->dateTime('horarioEmprestimo')->nullable();
            $table->dateTime('horarioDevolucao')->nullable();
        });
    }
}
