<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoldcreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goldcredits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('employee_id')->default(1);
            $table->float('gold_cad');
            $table->float('gold_usd');
            $table->date('gold_date');
            $table->float('credit_value');
            $table->text('note')->nullable();           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goldcredits');
    }
}
