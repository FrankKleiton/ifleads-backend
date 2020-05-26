<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemakeMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // I had to do this, because SQLite (on test environment) doesn't support many dropColumn or renameColumn.
        Schema::dropIfExists('materiais_perdidos');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('materiais');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable(false);
            $table->string('email', 60)->nullable(false)->unique();
            $table->string('password', 80)->nullable(false);
            $table->boolean('intern')->default(false);
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable(false);
            $table->mediumText('description')->nullable();
            $table->integer('amount')->default(1);

            // part of lost_materials
            $table->string('returner_registration_mark', 60)->nullable();
            $table->string('tooker_registration_mark', 60)->nullable();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('tooker_id')->notNullable();
            $table->dateTime('loan_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->softDeletes('deleted_at');
            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreignId('material_id');
            $table->foreign('material_id')
                ->references('id')
                ->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('users');

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->nullable(false);
            $table->string('email', 60)->nullable(false)->unique();
            $table->string('senha', 80)->nullable(false);
            $table->enum('role', ['1', '2', '3'])->default('1');
        });

        Schema::create('materiais', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->nullable(false);
            $table->boolean('emprestado')->default(false);
            $table->mediumText('descricao');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios')
                ->onUpdate('cascade');

            $table->dateTime('horarioEmprestimo')->nullable();
            $table->dateTime('horarioDevolucao')->nullable();
            $table->softDeletes('deleted_at');
        });

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

        Schema::create('materiais_perdidos', function (Blueprint $table) {
            $table->id();
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
}
