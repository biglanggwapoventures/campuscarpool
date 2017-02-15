<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_by');
            $table->enum('type', ['CAMPUS', 'HOME']);
            $table->integer('route_index')->default(0);
            $table->timestamp('departure_datetime');
            $table->string('place_id')->nullable();
            $table->string('place_formatted_address');
            $table->decimal('place_latitude', 9, 6);
            $table->decimal('place_longitude', 9, 6);
            $table->unsignedInteger('max_passenger');
            $table->decimal('fare_contribution', 13, 2);
            $table->string('additional_details')->nullable();
            $table->boolean('rating_done')->default(false);
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
        Schema::dropIfExists('driver_routes');
    }
}
