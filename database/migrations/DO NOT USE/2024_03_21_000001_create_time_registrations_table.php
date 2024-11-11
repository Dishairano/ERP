<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('time_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('color')->nullable();
      $table->text('description')->nullable();
      $table->boolean('is_billable')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('time_registrations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
      $table->foreignId('project_task_id')->nullable()->constrained()->onDelete('cascade');
      $table->foreignId('time_category_id')->constrained('time_categories')->onDelete('restrict');
      $table->dateTime('start_time');
      $table->dateTime('end_time')->nullable();
      $table->integer('duration_minutes');
      $table->integer('break_duration_minutes')->default(0);
      $table->boolean('is_billable')->default(true);
      $table->decimal('hourly_rate', 10, 2)->nullable();
      $table->text('description')->nullable();
      $table->string('status')->default('pending'); // pending, approved, rejected
      $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
      $table->dateTime('approved_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->json('metadata')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('time_registration_attachments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('time_registration_id')->constrained()->onDelete('cascade');
      $table->string('file_name');
      $table->string('file_path');
      $table->string('file_type');
      $table->integer('file_size');
      $table->timestamps();
    });

    Schema::create('time_registration_comments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('time_registration_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->text('comment');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('time_registration_settings', function (Blueprint $table) {
      $table->id();
      $table->string('setting_key')->unique();
      $table->json('setting_value');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('time_registration_comments');
    Schema::dropIfExists('time_registration_attachments');
    Schema::dropIfExists('time_registrations');
    Schema::dropIfExists('time_categories');
    Schema::dropIfExists('time_registration_settings');
  }
};
