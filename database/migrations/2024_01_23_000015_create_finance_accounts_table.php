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
    Schema::create('finance_accounts', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('type'); // asset, liability, equity, revenue, expense
      $table->string('category'); // current_asset, fixed_asset, etc.
      $table->foreignId('parent_id')->nullable()->constrained('finance_accounts')->nullOnDelete();
      $table->text('description')->nullable();
      $table->boolean('is_active')->default(true);
      $table->decimal('balance', 15, 2)->default(0);
      $table->decimal('opening_balance', 15, 2)->default(0);
      $table->string('currency', 3)->default('USD');
      $table->decimal('tax_rate', 5, 2)->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('type');
      $table->index('category');
      $table->index('is_active');
      $table->index(['parent_id', 'type']);
      $table->index(['type', 'category']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_accounts');
  }
};
