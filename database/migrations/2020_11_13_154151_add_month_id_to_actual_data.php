<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthIdToActualData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actual_data', function (Blueprint $table) {
            $table->unsignedInteger('month_id');
            $table->foreign('month_id')->references('id')->on('months')->onDelete('cascade')->onUpdate('cascade');
            $table->dropColumn('month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actual_data', function (Blueprint $table) {
            //
        });
    }
}
