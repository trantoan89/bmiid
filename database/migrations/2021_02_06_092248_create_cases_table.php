<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->date('case_date');
            $table->integer('active')->nullable();
            $table->integer('deceased')->nullable();
            $table->integer('recovered')->nullable();
            $table->string('status')->default('pending');
            $table->bigInteger('barangay_id')->unsigned();
            $table->bigInteger('disease_id')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->foreign('barangay_id')->references('id')->on('barangay')->onDelete("cascade");
            $table->foreign('disease_id')->references('id')->on('disease')->onDelete("cascade");
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('cases');
    }
}
