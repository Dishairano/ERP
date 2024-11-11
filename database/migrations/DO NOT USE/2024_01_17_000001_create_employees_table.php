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
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->foreignId('department_id')->constrained();
      $table->foreignId('position_id')->constrained();
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->enum('status', ['active', 'onboarding', 'offboarding', 'inactive'])->default('active');
      $table->string('address')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('postal_code')->nullable();
      $table->string('country')->nullable();
      $table->string('emergency_contact_name')->nullable();
      $table->string('emergency_contact_phone')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
