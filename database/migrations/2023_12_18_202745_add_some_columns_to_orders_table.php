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
    Schema::table('orders', function (Blueprint $table) {
      $table->string('shipping_address', 150)->nullable();
      $table->unsignedBigInteger('address_id')->nullable();
      $table->decimal('old_price', 15, 2)->nullable()
        ->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropColumn('shipping_address');
      $table->dropColumn('address_id');
    });
  }
};
