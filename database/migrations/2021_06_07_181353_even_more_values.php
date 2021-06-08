<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EvenMoreValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        schema::table('values', function($table) {
            $table->string('default')->nullable();
            $table->string('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::table('values', function($table) {
            $table->dropColumn('default');
            $table->dropColumn('order');
        });
    }
}
