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
    Schema::create('finance_report_sections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('report_id')->constrained('finance_reports')->cascadeOnDelete();
      $table->string('name');
      $table->string('type'); // accounts_list, calculation, custom_query
      $table->integer('sequence');
      $table->json('accounts')->nullable();
      $table->text('calculation')->nullable();
      $table->text('query')->nullable();
      $table->json('parameters')->nullable();
      $table->json('filters')->nullable();
      $table->json('grouping')->nullable();
      $table->json('sorting')->nullable();
      $table->boolean('show_subtotal')->default(false);
      $table->string('subtotal_label')->nullable();
      $table->boolean('show_total')->default(false);
      $table->string('total_label')->nullable();
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('type');
      $table->index('sequence');
      $table->index(['report_id', 'sequence']);
      $table->index(['report_id', 'type']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_report_sections');
  }
};
