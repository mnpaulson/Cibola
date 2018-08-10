<?php

use Illuminate\Database\Seeder;

class ValueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('values')->insert([
                'type_id' => 1,
                'name' => '8k',
        ]);

        DB::table('values')->insert([
                'type_id' => 1,
                'name' => '9k',
                'value1' => '0.375'
        ]);

        DB::table('values')->insert([
                'type_id' => 1,
                'name' => '10k',
                'value1' => '0.417'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '12k',
            'value1' => '0.5'
        ]);
        
        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '14k',
            'value1' => '0.585'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '18k',
            'value1' => '0.75'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '20k',
            'value1' => '0.833'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '22k',
            'value1' => '0.916'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => '24k',
            'value1' => '1'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => 'Diamonds',
            'value1' => '300'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => 'Platinum',
            'value1' => '0.95'
        ]);

        DB::table('values')->insert([
            'type_id' => 1,
            'name' => 'Other',
            'value1' => '5'
        ]);
    }
}
