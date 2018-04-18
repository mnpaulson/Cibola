<?php

use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'name' => 'Graham',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Elliot',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Carley',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Jill',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Kesley',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Mike',
            'active' => true,
        ]);

        DB::table('employees')->insert([
            'name' => 'Dave',
            'active' => false,
        ]);

        DB::table('employees')->insert([
            'name' => 'Christina',
            'active' => false,
        ]);
    }
}
