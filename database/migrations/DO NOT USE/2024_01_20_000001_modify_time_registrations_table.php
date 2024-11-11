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
    Schema::table('time_registrations', function (Blueprint $table) {
      // Add rejection columns first
      if (!Schema::hasColumn('time_registrations', 'rejected_by')) {
        $table->foreignId('rejected_by')->nullable()->constrained('users');
      }
      if (!Schema::hasColumn('time_registrations', 'rejected_at')) {
        $table->timestamp('rejected_at')->nullable();
      }

      // Then add week and month columns
      if (!Schema::hasColumn('time_registrations', 'week_number')) {
        $table->integer('week_number');
      }
      if (!Schema::hasColumn('time_registrations', 'month')) {
        $table->integer('month');
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('time_registrations', function (Blueprint $table) {
      $table->dropColumn(['week_number', 'month']);
      $table->dropForeign(['rejected_by']);
      $table->dropColumn(['rejected_by', 'rejected_at']);
    });
  }
};
