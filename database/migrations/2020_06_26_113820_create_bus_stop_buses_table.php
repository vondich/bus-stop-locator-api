<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusStopBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_stop_buses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bus_stop_id')->unsigned();
            $table->bigInteger('bus_id')->unsigned();
            $table->string('first_arrival_time', 5);
            $table->string('last_arrival_time', 5);
            $table->timestamps();

            $table->foreign('bus_stop_id')->references('id')->on('bus_stops');
            $table->foreign('bus_id')->references('id')->on('buses');

            $table->unique(['bus_stop_id', 'bus_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_stop_buses', function (Blueprint $table) {
            //
        });
    }
}
