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
    if (Schema::hasTable('project_phases')) {
      Schema::table('project_phases', function (Blueprint $table) {
        if (!Schema::hasColumn('project_phases', 'deleted_at')) {
          $table->softDeletes();
        }
      });
    }

    if (Schema::hasTable('project_tasks')) {
      Schema::table('project_tasks', function (Blueprint $table) {
        if (!Schema::hasColumn('project_tasks', 'deleted_at')) {
          $table->softDeletes();
        }
      });
    }

    if (Schema::hasTable('project_risks')) {
      Schema::table('project_risks', function (Blueprint $table) {
        if (!Schema::hasColumn('project_risks', 'deleted_at')) {
          $table->softDeletes();
        }
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('project_phases', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });

    Schema::table('project_tasks', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });

    Schema::table('project_risks', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });
  }
};
