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
    Schema::create('work_centers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->decimal('capacity', 10, 2);
      $table->decimal('efficiency', 5, 2)->default(100.00);
      $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
      $table->string('location')->nullable();
      $table->foreignId('supervisor_id')->nullable()->constrained('users');
      $table->json('maintenance_schedule')->nullable();
      $table->json('operating_hours')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('work_centers');
  }
};
