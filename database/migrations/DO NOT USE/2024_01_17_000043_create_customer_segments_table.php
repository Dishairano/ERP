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
    Schema::create('customer_segments', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->json('criteria');
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });

    Schema::create('customer_customer_segment', function (Blueprint $table) {
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->foreignId('customer_segment_id')->constrained()->onDelete('cascade');
      $table->primary(['customer_id', 'customer_segment_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_customer_segment');
    Schema::dropIfExists('customer_segments');
  }
};
