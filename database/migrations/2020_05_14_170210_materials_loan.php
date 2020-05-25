<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MaterialsLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('tooker_id')->notNullable();
            $table->dateTime('loan_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->softDeletes('deleted_at');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('usuarios');
            $table->foreignId('material_id');
            $table->foreign('material_id')->references('id')->on('materiais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loans');
    }
}
