<?php

use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            'nome' => 'franklynkleiton@gmail.com',
            'email' => 'franklynkleiton@gmail.com',
            'password' => bcrypt('1234567'),
            'role' => 1
        ]);
    }
}
