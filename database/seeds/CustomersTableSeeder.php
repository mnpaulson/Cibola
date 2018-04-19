<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Customer::class, 2000)->create()->each(function($u) {
            $u->jobs()->saveMany(factory(App\Job::class, rand(1,15))->make());

            // 3rd nest seeding
            $u->jobs()->each(function($jobs){
                $jobs->job_images()->saveMany(factory(App\Job_image::class, rand(1,6))->make());
            });
        });

    }
}

    