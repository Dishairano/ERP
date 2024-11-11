<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    // First add all new columns
    Schema::table('activities', function (Blueprint $table) {
      if (!Schema::hasColumn('activities', 'user_id')) {
        $table->foreignId('user_id')->nullable()->after('id');
      }
      if (!Schema::hasColumn('activities', 'project_id')) {
        $table->foreignId('project_id')->nullable()->after('user_id');
      }
      if (!Schema::hasColumn('activities', 'action')) {
        $table->string('action')->nullable()->after('type');
      }
      if (!Schema::hasColumn('activities', 'subject_type')) {
        $table->string('subject_type')->nullable()->after('description');
      }
      if (!Schema::hasColumn('activities', 'subject_id')) {
        $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
      }
      if (!Schema::hasColumn('activities', 'metadata')) {
        $table->json('metadata')->nullable()->after('description');
      }
      if (!Schema::hasColumn('activities', 'changes')) {
        $table->json('changes')->nullable()->after('metadata');
      }
      if (!Schema::hasColumn('activities', 'deleted_at')) {
        $table->softDeletes();
      }
    });

    // Copy data from old columns to new ones
    DB::table('activities')->update([
      'user_id' => DB::raw('performed_by'),
      'metadata' => DB::raw('data'),
      'subject_type' => DB::raw('activitable_type'),
      'subject_id' => DB::raw('activitable_id')
    ]);

    // Add foreign key constraints
    Schema::table('activities', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    });

    // Modify existing columns
    Schema::table('activities', function (Blueprint $table) {
      $table->text('description')->change();
    });
  }

  public function down()
  {
    Schema::table('activities', function (Blueprint $table) {
      // Drop foreign keys first
      $table->dropForeign(['user_id']);
      $table->dropForeign(['project_id']);

      // Drop new columns
      $table->dropColumn([
        'user_id',
        'project_id',
        'action',
        'subject_type',
        'subject_id',
        'metadata',
        'changes',
        'deleted_at'
      ]);

      // Restore description type
      $table->string('description')->change();
    });
  }
};
