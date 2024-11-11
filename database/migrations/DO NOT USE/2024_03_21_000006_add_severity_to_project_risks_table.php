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
    // Migration removed since severity is already included in create_project_risks_table
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // No changes to reverse
  }
};
