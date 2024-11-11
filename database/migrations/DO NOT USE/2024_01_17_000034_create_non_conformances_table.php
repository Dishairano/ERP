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
    Schema::create('non_conformances', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->foreignId('work_order_id')->constrained('production_orders');
      $table->foreignId('reporter_id')->constrained('users');
      $table->string('type');
      $table->enum('severity', ['minor', 'major', 'critical']);
      $table->text('description');
      $table->text('immediate_action');
      $table->text('root_cause')->nullable();
      $table->text('corrective_action')->nullable();
      $table->text('preventive_action')->nullable();
      $table->enum('status', ['open', 'investigating', 'resolved', 'closed'])->default('open');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('non_conformances');
  }
};
