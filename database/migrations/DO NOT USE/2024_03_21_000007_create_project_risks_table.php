<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Skip if table already exists
    if (Schema::hasTable('project_risks')) {
      // Add any missing columns
      Schema::table('project_risks', function (Blueprint $table) {
        if (!Schema::hasColumn('project_risks', 'deleted_at')) {
          $table->softDeletes();
        }
        if (!Schema::hasColumn('project_risks', 'contingency_plan')) {
          $table->text('contingency_plan')->nullable();
        }
        if (!Schema::hasColumn('project_risks', 'due_date')) {
          $table->date('due_date')->nullable();
        }
      });
      return;
    }

    Schema::create('project_risks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->text('description');
      $table->integer('probability');
      $table->integer('impact');
      $table->string('severity')->default('low');
      $table->text('mitigation_strategy');
      $table->text('contingency_plan')->nullable();
      $table->string('status');
      $table->date('due_date')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    // Don't drop the table if it was created by another migration
    if (Schema::hasTable('project_risks')) {
      Schema::table('project_risks', function (Blueprint $table) {
        $table->dropSoftDeletesIfExists();
        $table->dropColumnIfExists('contingency_plan');
        $table->dropColumnIfExists('due_date');
      });
    }
  }
};
