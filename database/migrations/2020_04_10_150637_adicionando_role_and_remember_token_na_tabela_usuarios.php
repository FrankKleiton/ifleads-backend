<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdicionandoRoleAndRememberTokenNaTabelaUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            
            /**
             * Explanation for the use of default value
             * 
             * It was added default value to role due to SQLite bug that not 
             * allow not nullable column without it. 
             */
            $table->tinyInteger('role')->nullable(false)->default(2);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('remember_token');
        });
    }
}
