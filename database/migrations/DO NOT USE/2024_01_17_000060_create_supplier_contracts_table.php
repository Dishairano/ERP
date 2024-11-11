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
    Schema::create('supplier_contracts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->constrained();
      $table->string('type');
      $table->date('start_date');
      $table->date('end_date');
      $table->json('terms');
      $table->decimal('value', 12, 2);
      $table->string('payment_terms');
      $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('supplier_contracts');
  }
};
