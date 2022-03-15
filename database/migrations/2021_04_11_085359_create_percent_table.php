<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePercentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('percent', function (Blueprint $table) {
            $table->id();
            $table->integer('low_risk');
            $table->integer('severe_risk');
            $table->integer('high_risk');
            $table->bigInteger('disease_id')->unsigned();
            $table->foreign('disease_id')->references('id')->on('disease')->onDelete("cascade");
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
        Schema::dropIfExists('percent');
    }
}
