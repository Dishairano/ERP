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
    Schema::create('chart_measurements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('control_chart_id')->constrained()->onDelete('cascade');
      $table->decimal('value', 10, 4);
      $table->timestamp('measured_at');
      $table->foreignId('measured_by')->constrained('users');
      $table->boolean('is_out_of_control')->default(false);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('chart_measurements');
  }
};
