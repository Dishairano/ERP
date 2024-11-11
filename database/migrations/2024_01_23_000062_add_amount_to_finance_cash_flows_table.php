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
    Schema::table('finance_cash_flows', function (Blueprint $table) {
      $table->decimal('amount', 15, 2)->after('id')->default(0);
      if (!Schema::hasColumn('finance_cash_flows', 'date')) {
        $table->timestamp('date')->after('amount')->nullable();
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('finance_cash_flows', function (Blueprint $table) {
      $table->dropColumn('amount');
      if (Schema::hasColumn('finance_cash_flows', 'date')) {
        $table->dropColumn('date');
      }
    });
  }
};
