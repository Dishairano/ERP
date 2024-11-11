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
    Schema::create('activities', function (Blueprint $table) {
      $table->id();
      $table->string('type');
      $table->text('description');
      $table->string('activitable_type');
      $table->unsignedBigInteger('activitable_id');
      $table->foreignId('performed_by')->constrained('users');
      $table->timestamp('performed_at');
      $table->json('data')->nullable();
      $table->timestamps();

      $table->index(['activitable_type', 'activitable_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('activities');
  }
};
