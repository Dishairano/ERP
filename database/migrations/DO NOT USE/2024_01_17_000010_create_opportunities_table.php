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
    Schema::create('opportunities', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->foreignId('contact_id')->constrained();
      $table->foreignId('lead_id')->nullable()->constrained();
      $table->string('stage');
      $table->string('status');
      $table->decimal('value', 12, 2);
      $table->integer('probability');
      $table->date('expected_close_date');
      $table->date('actual_close_date')->nullable();
      $table->string('source')->nullable();
      $table->text('description')->nullable();
      $table->foreignId('assigned_to')->nullable()->constrained('users');
      $table->string('lost_reason')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('opportunities');
  }
};
