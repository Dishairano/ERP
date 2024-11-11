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
    Schema::create('dashboard_analytics', function (Blueprint $table) {
      $table->id();
      $table->string('metric_name');
      $table->decimal('metric_value', 15, 2);
      $table->string('metric_type');
      $table->string('time_period');
      $table->decimal('comparison_value', 15, 2)->nullable();
      $table->decimal('percentage_change', 8, 2)->nullable();
      $table->string('status')->nullable();
      $table->json('chart_data')->nullable();
      $table->timestamp('last_updated_at');
      $table->timestamps();

      // Indexes for better query performance
      $table->index('metric_name');
      $table->index('metric_type');
      $table->index('time_period');
      $table->index('status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('dashboard_analytics');
  }
};
