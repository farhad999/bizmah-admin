<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('carousel_slides', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('carousel_id');
      $table->string('image');
      $table->string('title')->nullable();
      $table->string('button_text')->nullable();
      $table->string('button_link')->nullable();
      $table->integer('order')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('carousel_slides');
  }
};
