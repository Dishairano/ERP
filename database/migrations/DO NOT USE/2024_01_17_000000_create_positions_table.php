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
    Schema::create('positions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->foreignId('department_id')->constrained();
      $table->text('description')->nullable();
      $table->json('requirements')->nullable();
      $table->json('responsibilities')->nullable();
      $table->decimal('salary_range_min', 12, 2)->nullable();
      $table->decimal('salary_range_max', 12, 2)->nullable();
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('positions');
  }
};
