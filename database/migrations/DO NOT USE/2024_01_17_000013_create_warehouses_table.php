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
    Schema::create('warehouses', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->string('address');
      $table->string('city');
      $table->string('state');
      $table->string('country');
      $table->string('postal_code');
      $table->foreignId('manager_id')->nullable()->constrained('users');
      $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
      $table->decimal('capacity', 10, 2);
      $table->string('type');
      $table->json('operating_hours')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('warehouses');
  }
};
