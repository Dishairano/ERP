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
    Schema::create('data_analyses', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('dataset_id')->constrained();
      $table->string('type');
      $table->json('parameters');
      $table->text('description')->nullable();
      $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
      $table->json('results')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_analyses');
  }
};
