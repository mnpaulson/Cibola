<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('values', function (Blueprint $table) {
            $table->string('discount')->nullable();
            $table->string('markup')->nullable();
            // $table->string('metalValue')->nullable();
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
            $table->dropColumn('discount');
            $table->dropColumn('markup');
            // $table->dropColumn('metalValue');
        });
    }
}
