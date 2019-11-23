<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('est_values', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('estimate_id');
            $table->string('name')->default('unknown');
            $table->string('type');
            $table->string('priceType')->nullable();
            $table->float('amt')->default(0);
            $table->float('pricePer')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('est_values');
    }
}
