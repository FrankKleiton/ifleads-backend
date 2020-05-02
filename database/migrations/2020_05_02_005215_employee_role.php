<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeRole extends Migration
{
    /**
     * Run the migrations.
     *
     * The up method of this migration will add
     * a role column of type enum to the usuarios 
     * table, each role is represented by a integer
     * number which represent the employee's role.
     * 
     * The number 1 belong to the Admin role
     * 
     * The number 2 belong to the Employee (At least 
     * the conventional employee) role
     * 
     * The number 3 belong to the Intern role
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('role', [1, 2, 3]);
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
        });
    }
}
