<?php

use Illuminate\Database\Seeder;

class KotenSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kotens')->insert([
            'kot_id' => 'k@test123',
            'pass' => 'test123',
        ]);

        DB::table('users')->insert([
            'name' => 'Jonas Van Rth',
            'email' => 'test@gmail.com',
        ]);
    }
}
