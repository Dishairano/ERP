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
    Schema::create('data_visualizations', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('dataset_id')->constrained();
      $table->string('type');
      $table->json('configuration');
      $table->text('description')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_visualizations');
  }
};
