<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
  {
    Schema::create('otps', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('mobile');
      $table->string('code');
      $table->string('last_otp_time');
      $table->boolean('is_tries')->nullable();
      $table->integer('timeout');
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
    Schema::dropIfExists('otps');
  }
};
