<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoachIdToCoachRowsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('coach_rows', function (Blueprint $table) {
      $table->bigInteger('coach_id')->unsigned()->default(0);
      $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('coach_rows', function (Blueprint $table) {
      //
    });
  }
}
