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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->string('sku')->unique();
      $table->unsignedBigInteger('category_id')->nullable();
      $table->unsignedBigInteger('sub_category_id')->nullable();
      $table->unsignedBigInteger('sub_sub_category_id')->nullable();
      $table->unsignedBigInteger('brand_id')->nullable();
      $table->enum('type', ['single', 'variable'])->default('single');
      $table->longText('short_description')->nullable();
      $table->longText('description')->nullable();
      $table->string('template')->nullable();
      $table->string('image')->nullable();
      $table->unsignedBigInteger('added_by')->nullable();
      $table->boolean('visibility')->default(1);
      $table->softDeletes();
      $table->timestamps();

      $table->foreign('category_id')->references('id')
        ->on('categories')->nullOnDelete();
      $table->foreign('sub_category_id')->references('id')
        ->on('categories')->nullOnDelete();
      $table->foreign('sub_sub_category_id')->references('id')
        ->on('categories')->nullOnDelete();
      $table->foreign('brand_id')->references('id')
        ->on('brands')->nullOnDelete();

      $table->foreign('added_by')
        ->references('id')->on('users')
        ->nullOnDelete();

    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
