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
    Schema::create('finance_asset_depreciations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('asset_id')->constrained('finance_assets')->cascadeOnDelete();
      $table->date('date');
      $table->decimal('amount', 15, 2);
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('date');
      $table->index(['asset_id', 'date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_asset_depreciations');
  }
};
