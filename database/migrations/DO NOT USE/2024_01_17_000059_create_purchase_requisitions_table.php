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
    Schema::create('purchase_requisitions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('department_id')->constrained();
      $table->foreignId('requester_id')->constrained('users');
      $table->date('required_date');
      $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
      $table->string('reason');
      $table->enum('status', ['pending', 'approved', 'rejected', 'ordered'])->default('pending');
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('purchase_requisitions');
  }
};
