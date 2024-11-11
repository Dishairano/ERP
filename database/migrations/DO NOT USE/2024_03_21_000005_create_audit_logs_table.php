<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('audit_logs', function (Blueprint $table) {
      $table->id();
      $table->string('event_type'); // create, update, delete, login, logout, etc.
      $table->string('auditable_type');
      $table->unsignedBigInteger('auditable_id')->nullable();
      $table->foreignId('user_id')->nullable()->constrained();
      $table->string('ip_address')->nullable();
      $table->string('user_agent')->nullable();
      $table->json('old_values')->nullable();
      $table->json('new_values')->nullable();
      $table->json('metadata')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
      $table->index(['auditable_type', 'auditable_id']);
      $table->index('event_type');
      $table->index('created_at');
    });

    Schema::create('audit_log_archives', function (Blueprint $table) {
      $table->id();
      $table->date('archive_date');
      $table->unsignedInteger('records_count');
      $table->string('file_path');
      $table->string('status'); // pending, completed, failed
      $table->text('error_message')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('audit_log_archives');
    Schema::dropIfExists('audit_logs');
  }
};
