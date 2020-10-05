<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewEstValuesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        schema::table('est_values', function($table) {
            $table->float('markup')->default(0);
            $table->float('discount')->default(0);
            $table->float('priceModifier')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::table('est_values', function($table) {
            $table->dropColumn('markup');
            $table->dropColumn('discount');
            $table->dropColumn('priceModifier');
        });
    }
}
