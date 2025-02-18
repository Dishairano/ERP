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
    Schema::create('journals', function (Blueprint $table) {
      $table->id();
      $table->string('number')->unique();
      $table->date('date');
      $table->string('description');
      $table->enum('status', ['draft', 'posted', 'voided'])->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('journals');
  }
};
