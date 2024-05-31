<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlooddonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blooddonors', function (Blueprint $table) {
            $table->id();
            $table->integer('district_id')->unsigned();
            $table->integer('upazilla_id')->unsigned();
            $table->string('name');
            $table->string('category');
            $table->string('mobile');
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
        Schema::dropIfExists('blooddonors');
    }
}
