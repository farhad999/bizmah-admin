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
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique()->index();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->string('image');
      $table->text('description')->nullable();
      $table->boolean('status')->default(1);
      $table->boolean('visibility')->default(1);
      $table->integer('level')->default(0);
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('parent_id')->references('id')
        ->on('categories')->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('categories');
  }
};
