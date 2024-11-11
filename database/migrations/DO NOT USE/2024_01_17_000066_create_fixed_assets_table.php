<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('fixed_assets', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('category_id')->constrained('asset_categories');
      $table->foreignId('location_id')->constrained('locations');
      $table->date('purchase_date');
      $table->decimal('purchase_cost', 12, 2);
      $table->integer('useful_life')->comment('in years');
      $table->string('depreciation_method');
      $table->decimal('salvage_value', 12, 2);
      $table->enum('status', ['active', 'disposed', 'maintenance'])->default('active');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fixed_assets');
  }
};
