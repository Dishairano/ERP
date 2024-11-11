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
    Schema::create('budgets', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->decimal('amount', 15, 2);
      $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('cost_category_id')->nullable()->constrained('cost_categories')->onDelete('set null');
      $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
      $table->date('start_date');
      $table->date('end_date');
      $table->string('status')->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('updated_by')->nullable()->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('budgets');
  }
};
