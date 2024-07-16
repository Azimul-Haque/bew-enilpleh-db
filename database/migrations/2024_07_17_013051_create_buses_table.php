<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->integer('from_district')->unsigned();
            $table->integer('to_district')->unsigned();
            $table->string('bus_name');
            $table->string('route_info');
            $table->string('bus_type');
            $table->string('fare');
            $table->string('starting_time');
            $table->string('contact');
            $table->timestamps();

            $table->foreign('from_district')->references('district_id')->on('districts');
            $table->foreign('to_district')->references('district_id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}