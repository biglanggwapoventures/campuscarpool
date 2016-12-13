<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_by');
            $table->enum('trip_type', ['CAMPUS', 'HOME', 'OTHERS']);
            $table->timestamp('departure_timestamp');
            $table->string('place_id');
            $table->string('place_address');
            $table->decimal('place_latitude', 9, 6);
            $table->decimal('place_longitude', 9, 6);
            $table->unsignedInteger('max_passenger');
            $table->decimal('fare_contribution', 13, 2);
            $table->string('additional_details');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
