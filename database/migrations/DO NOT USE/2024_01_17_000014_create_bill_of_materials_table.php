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
    Schema::create('bill_of_materials', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->string('version');
      $table->text('description')->nullable();
      $table->date('effective_date');
      $table->enum('status', ['draft', 'active', 'obsolete'])->default('draft');
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamp('approved_at')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['product_id', 'version']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bill_of_materials');
  }
};
