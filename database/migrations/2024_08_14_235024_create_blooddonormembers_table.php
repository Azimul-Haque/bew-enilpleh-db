<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlooddonormembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blooddonormembers', function (Blueprint $table) {
            $table->id();
            $table->integer('blooddonor_id')->unsigned();
            $table->string('name');
            $table->string('designation');
            $table->string('address');
            $table->string('contact');
            $table->timestamps();

            $table->foreign('blooddonor_id')->references('blooddonor_id')->on('blooddonors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blooddonormembers');
    }
}
