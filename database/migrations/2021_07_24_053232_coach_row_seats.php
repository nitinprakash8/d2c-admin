<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoachRowSeats extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('row_seats', function (Blueprint $table) {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('row_id')->unsigned()->default(0);
      $table->integer('number');
      $table->boolean('booked')->default(0);
      $table->timestamps();
      $table->foreign('row_id')->references('id')->on('coach_rows')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('row_seats');
  }
}
