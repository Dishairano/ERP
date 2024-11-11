<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('budgets', function (Blueprint $table) {
      // Only add columns if they don't exist
      if (!Schema::hasColumn('budgets', 'alert_threshold')) {
        $table->decimal('alert_threshold', 8, 2)->nullable();
      }
      if (!Schema::hasColumn('budgets', 'warning_threshold')) {
        $table->decimal('warning_threshold', 8, 2)->nullable();
      }
      if (!Schema::hasColumn('budgets', 'notification_email')) {
        $table->string('notification_email')->nullable();
      }
      if (!Schema::hasColumn('budgets', 'notification_frequency')) {
        $table->string('notification_frequency')->default('daily');
      }
      if (!Schema::hasColumn('budgets', 'last_notification_sent')) {
        $table->timestamp('last_notification_sent')->nullable();
      }
      if (!Schema::hasColumn('budgets', 'is_monitoring_active')) {
        $table->boolean('is_monitoring_active')->default(true);
      }
    });
  }

  public function down()
  {
    Schema::table('budgets', function (Blueprint $table) {
      $table->dropColumn([
        'alert_threshold',
        'warning_threshold',
        'notification_email',
        'notification_frequency',
        'last_notification_sent',
        'is_monitoring_active'
      ]);
    });
  }
};
