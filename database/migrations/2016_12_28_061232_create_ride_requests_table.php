<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRideRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('driver_route_id');
            $table->unsignedInteger('commuter_id');
            $table->unsignedInteger('num_seats')->default(1);

            $table->decimal('location_longitude', 9, 6);
            $table->decimal('location_latitude', 9, 6);
            $table->string('location_address')->default('');

            $table->boolean('accepted')->default(false);
            $table->boolean('rejected')->default(false);

            $table->enum('driver_rating', range(0, 5))->default(0);
            $table->enum('commuter_rating', range(0, 5))->default(0);

            $table->timestamp('cancelled_at')->nullable();
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
        Schema::dropIfExists('ride_requests');
    }
}
