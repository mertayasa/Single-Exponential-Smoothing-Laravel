<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('month_id');
            $table->double('forecast', 15,2);
            $table->double('alpha', 1,1);
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forecasts');
    }
}
