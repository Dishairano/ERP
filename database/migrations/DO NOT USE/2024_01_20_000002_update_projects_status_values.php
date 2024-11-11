<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Update existing status values to match new format
    DB::table('projects')
      ->where('status', 'planned')
      ->update(['status' => 'draft']);

    // Note: We keep the string type for status to maintain flexibility
    // while documenting the standard values in comments

    // Standard status values:
    // - draft
    // - active
    // - on_hold
    // - completed
    // - cancelled
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Revert draft status back to planned
    DB::table('projects')
      ->where('status', 'draft')
      ->update(['status' => 'planned']);
  }
};
