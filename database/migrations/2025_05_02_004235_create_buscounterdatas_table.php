<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuscounterdatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buscounterdatas', function (Blueprint $table) {
            $table->id();
            $table->integer('bus_id')->unsigned();
            $table->integer('buscounter_id')->unsigned();
            $table->string('address');
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
        Schema::dropIfExists('buscounterdatas');
    }
}
