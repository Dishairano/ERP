<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('system_backups', function (Blueprint $table) {
      $table->id();
      $table->string('backup_name');
      $table->string('file_path');
      $table->string('backup_type'); // full, incremental, differential
      $table->bigInteger('file_size')->nullable();
      $table->string('status'); // pending, in_progress, completed, failed
      $table->text('error_message')->nullable();
      $table->json('included_tables')->nullable();
      $table->json('excluded_tables')->nullable();
      $table->json('backup_metadata')->nullable();
      $table->timestamp('started_at')->nullable();
      $table->timestamp('completed_at')->nullable();
      $table->foreignId('initiated_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('backup_schedules', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('frequency'); // daily, weekly, monthly
      $table->string('backup_type');
      $table->time('scheduled_time');
      $table->boolean('is_active')->default(true);
      $table->json('configuration')->nullable();
      $table->timestamp('last_run')->nullable();
      $table->timestamp('next_run')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('backup_restorations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('system_backup_id')->constrained();
      $table->string('status'); // pending, in_progress, completed, failed
      $table->text('error_message')->nullable();
      $table->json('restored_tables')->nullable();
      $table->json('restoration_metadata')->nullable();
      $table->timestamp('started_at')->nullable();
      $table->timestamp('completed_at')->nullable();
      $table->foreignId('initiated_by')->constrained('users');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('backup_restorations');
    Schema::dropIfExists('backup_schedules');
    Schema::dropIfExists('system_backups');
  }
};
