<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->integer('district_id')->unsigned();
            $table->integer('upazilla_id')->unsigned();
            $table->string('name');
            $table->text('investigation_data')->nullable();
            $table->string('degree2')->nullable();
            $table->string('degree3')->nullable();
            $table->string('serial');
            $table->string('address');
            $table->string('helpline');
            $table->json('weekdays')->nullable();
            $table->string('timefrom')->nullable();
            $table->string('timeto')->nullable();
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
        Schema::dropIfExists('doctors');
    }
}
