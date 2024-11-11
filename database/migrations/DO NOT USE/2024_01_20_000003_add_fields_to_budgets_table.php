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
    Schema::table('budgets', function (Blueprint $table) {
      // Add new columns
      $table->decimal('spent_amount', 15, 2)->default(0)->after('amount');
      $table->json('custom_fields')->nullable()->after('status');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
      $table->timestamp('approved_at')->nullable()->after('approved_by');

      // Drop existing foreign keys to modify them
      $table->dropForeign(['department_id']);
      $table->dropForeign(['project_id']);

      // Add new foreign key constraints
      $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete()->change();
      $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('budgets', function (Blueprint $table) {
      // Drop new columns
      $table->dropColumn(['spent_amount', 'custom_fields', 'approved_by', 'approved_at']);

      // Drop modified foreign keys
      $table->dropForeign(['department_id']);
      $table->dropForeign(['project_id']);

      // Restore original foreign key constraints
      $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
      $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();
    });
  }
};
