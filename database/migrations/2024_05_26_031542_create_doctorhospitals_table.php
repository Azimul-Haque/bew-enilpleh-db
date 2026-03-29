<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorhospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctorhospitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->unsigned();
            $table->foreignId('hospital_id')->unsigned();
            $table->timestamps();

            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('cascade');

            $table->foreign('hospital_id')
                  ->references('id')
                  ->on('hospitals')
                  ->onDelete('cascade');

            $table->string('address_or_room')->nullable();
            $table->string('serial_phone')->nullable(); 
            $table->text('weekdays')->nullable();
            $table->json('ondays')->nullable();
            $table->integer('onlineserial');
            $table->integer('is_chamber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctorhospitals');
    }
}
