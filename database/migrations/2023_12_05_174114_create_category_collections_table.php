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
    Schema::create('category_collections', function (Blueprint $table) {
      $table->id();
      $table->string('category_id');
      $table->string('type');
      $table->integer('order')->default(1);
      $table->timestamps();

      $table->foreign('category_id')->references('id')
        ->on('categories')->onDelete('cascade');

    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('category_collections');
  }
};
