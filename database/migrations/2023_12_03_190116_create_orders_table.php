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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('order_no');
      $table->unsignedBigInteger('customer_id')->nullable();
      $table->string('customer_name');
      $table->string('customer_mobile');
      $table->string('customer_address');
      $table->string('customer_city');
      $table->string('customer_zone');
      $table->decimal('subtotal', 15, 2);
      $table->decimal('shipping_charge', 15, 2);
      $table->decimal('discount', 15, 2)->nullable();
      $table->decimal('total_amount', 15, 2);
      $table->string('payment_method')->nullable();
      $table->string('payment_status')->nullable();
      $table->string('status')->default('pending');
      $table->string('shipping_status')->default('processing');
      $table->string('note')->nullable();
      $table->string('delivered_to')->nullable();
      $table->string('source')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
