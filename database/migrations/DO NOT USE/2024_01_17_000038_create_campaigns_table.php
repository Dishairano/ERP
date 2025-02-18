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
    Schema::create('campaigns', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->decimal('budget', 12, 2);
      $table->json('target_audience');
      $table->json('goals');
      $table->text('description')->nullable();
      $table->enum('status', ['draft', 'active', 'paused', 'completed'])->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('campaigns');
  }
};
