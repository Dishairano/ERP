<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Skip if table already exists
    if (Schema::hasTable('project_templates')) {
      // Add any missing columns
      Schema::table('project_templates', function (Blueprint $table) {
        if (!Schema::hasColumn('project_templates', 'created_by')) {
          $table->foreignId('created_by')->constrained('users');
        }
        if (!Schema::hasColumn('project_templates', 'updated_by')) {
          $table->foreignId('updated_by')->constrained('users');
        }
        if (!Schema::hasColumn('project_templates', 'deleted_at')) {
          $table->softDeletes();
        }
      });
      return;
    }
  }

  public function down()
  {
    // Don't drop the table if it was created by another migration
    if (Schema::hasTable('project_templates')) {
      Schema::table('project_templates', function (Blueprint $table) {
        $table->dropColumnIfExists('created_by');
        $table->dropColumnIfExists('updated_by');
        $table->dropSoftDeletes();
      });
    }
  }
};
