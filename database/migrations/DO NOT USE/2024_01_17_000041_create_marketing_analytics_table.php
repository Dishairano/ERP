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
    Schema::create('marketing_analytics', function (Blueprint $table) {
      $table->id();
      $table->foreignId('campaign_id')->constrained();
      $table->json('metrics');
      $table->string('period');
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('roi', 8, 2);
      $table->decimal('conversion_rate', 5, 2);
      $table->decimal('cost_per_lead', 10, 2);
      $table->decimal('cost_per_acquisition', 10, 2);
      $table->timestamps();

      // Use a shorter custom index name
      $table->unique(['campaign_id', 'period', 'start_date', 'end_date'], 'mkt_analytics_period_unique');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('marketing_analytics');
  }
};
