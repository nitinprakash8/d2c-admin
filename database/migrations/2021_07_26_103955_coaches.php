<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Coaches extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('coaches', function (Blueprint $table) {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('train_id')->unsigned()->default(0);
      $table->string('number');
      $table->timestamps();
      $table->foreign('train_id')->references('id')->on('trains')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('coaches');
  }
}
