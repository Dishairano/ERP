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
    Schema::create('quality_standards', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->string('name');
      $table->text('description')->nullable();
      $table->json('specifications');
      $table->string('version');
      $table->date('effective_date');
      $table->enum('status', ['draft', 'active', 'obsolete'])->default('draft');
      $table->foreignId('approver_id')->nullable()->constrained('users');
      $table->timestamps();

      $table->unique(['product_id', 'version']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('quality_standards');
  }
};
