<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('budgets', function (Blueprint $table) {
      // Skip department_id as it already exists
      // Add only new columns
      $table->decimal('forecast_amount', 15, 2)->nullable();
      $table->decimal('variance', 15, 2)->nullable();
      $table->json('monthly_distribution')->nullable();
      $table->boolean('is_recurring')->default(false);
      $table->string('recurrence_pattern')->nullable();
      $table->json('budget_limits')->nullable();
      $table->json('alert_thresholds')->nullable();
      $table->string('currency_code')->nullable();
      $table->decimal('exchange_rate', 10, 4)->nullable();
      $table->json('attachments')->nullable();
      $table->text('notes')->nullable();
      $table->json('custom_fields')->nullable();
      $table->string('approval_status')->default('pending');
      $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
      $table->timestamp('approved_at')->nullable();
      $table->json('revision_history')->nullable();
      $table->integer('version')->default(1);
      $table->json('tags')->nullable();
    });
  }

  public function down()
  {
    Schema::table('budgets', function (Blueprint $table) {
      $table->dropColumn([
        'forecast_amount',
        'variance',
        'monthly_distribution',
        'is_recurring',
        'recurrence_pattern',
        'budget_limits',
        'alert_thresholds',
        'currency_code',
        'exchange_rate',
        'attachments',
        'notes',
        'custom_fields',
        'approval_status',
        'approved_by',
        'approved_at',
        'revision_history',
        'version',
        'tags'
      ]);
    });
  }
};
