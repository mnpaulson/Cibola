<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('customer_id');
            $table->integer('employee_id')->default(1);
            $table->float('estimate')->default(0);
            $table->string('est_note')->nullable();
            $table->text('note')->nullable();            
            $table->boolean('appraisal');
            $table->boolean('vital_date');            
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
