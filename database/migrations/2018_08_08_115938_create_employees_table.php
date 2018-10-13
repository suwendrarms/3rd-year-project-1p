<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo');
            $table->string('code');
            $table->string('name');
          
            $table->tinyInteger('gender');
            $table->date('date_of_birth');
            $table->date('date_of_joining');
            $table->string('number');
            $table->string('qualification');
            $table->string('emergency_number');
          
            $table->string('father_name');
            $table->string('current_address');
            $table->string('permanent_address');
          
            $table->string('probation_period');
            $table->date('date_of_confirmation');
            $table->string('department');
            $table->string('salary');
            $table->string('account_number');
            $table->string('bank_name');
           
            $table->date('date_of_resignation');
            $table->string('notice_period');
        
            $table->tinyInteger('full_final');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('employees');
    }
}
