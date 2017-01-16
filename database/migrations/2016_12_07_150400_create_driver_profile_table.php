<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('vehicle_type', ['MOTORCYCLE', 'CAR']);
            $table->string('vehicle_model');
            $table->string('vehicle_plate_number')->unique();
            $table->string('drivers_license_filename');
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
        Schema::dropIfExists('driver_profile');
    }
}
