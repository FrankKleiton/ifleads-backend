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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable(false);
            $table->string('email', 60)->nullable(false)->unique();
            $table->string('password', 80)->nullable(false);
            $table->enum('role', ['admin', 'employee', 'intern'])
                ->default('employee');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable(false);
            $table->mediumText('description')->nullable();
            $table->integer('amount')->default(1);
            $table->softDeletes('deleted_at', 0);

            // part of lost_materials
            $table->string('returner_registration_mark', 60)->nullable();
            $table->string('tooker_registration_mark', 60)->nullable();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('tooker_id', 60)->nullable(false);
            $table->dateTime('loan_time')->nullable(false);
            $table->dateTime('return_time')->nullable();
            $table->integer('material_amount')->nullable();
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
    }
}
