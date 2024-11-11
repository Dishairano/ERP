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
    Schema::create('finance_assets', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('category'); // land, buildings, machinery, equipment, vehicles, furniture, computers, software, other
      $table->date('purchase_date');
      $table->decimal('purchase_cost', 15, 2);
      $table->decimal('salvage_value', 15, 2)->default(0);
      $table->integer('useful_life_years');
      $table->string('depreciation_method'); // straight_line, declining_balance, sum_of_years_digits
      $table->decimal('depreciation_rate', 7, 4)->nullable();
      $table->date('last_depreciation_date')->nullable();
      $table->decimal('accumulated_depreciation', 15, 2)->default(0);
      $table->decimal('current_value', 15, 2);
      $table->string('location')->nullable();
      $table->string('status')->default('active'); // active, disposed, written_off
      $table->date('disposal_date')->nullable();
      $table->decimal('disposal_value', 15, 2)->nullable();
      $table->text('description')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('category');
      $table->index('status');
      $table->index('purchase_date');
      $table->index('last_depreciation_date');
      $table->index(['category', 'status']);
      $table->index(['status', 'last_depreciation_date']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_assets');
  }
};
