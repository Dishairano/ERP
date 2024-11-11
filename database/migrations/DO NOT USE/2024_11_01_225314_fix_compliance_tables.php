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
    // Drop the tables if they exist
    Schema::dropIfExists('compliance_trainings');
    Schema::dropIfExists('compliance_notifications');
    Schema::dropIfExists('compliance_documents');
    Schema::dropIfExists('compliance_audits');
    Schema::dropIfExists('compliance_requirements');

    // Create compliance_requirements table
    Schema::create('compliance_requirements', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->string('status');
      $table->date('due_date');
      $table->string('priority');
      $table->string('category');
      $table->text('compliance_details');
      $table->timestamps();
      $table->softDeletes();
    });

    // Create compliance_audits table
    Schema::create('compliance_audits', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->date('audit_date');
      $table->string('status');
      $table->string('auditor');
      $table->text('findings');
      $table->text('recommendations');
      $table->timestamps();
      $table->softDeletes();
    });

    // Create compliance_documents table
    Schema::create('compliance_documents', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->string('document_type');
      $table->string('file_path');
      $table->date('expiry_date')->nullable();
      $table->string('status');
      $table->timestamps();
      $table->softDeletes();
    });

    // Create compliance_notifications table
    Schema::create('compliance_notifications', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('message');
      $table->string('type');
      $table->string('status');
      $table->timestamp('read_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Create compliance_trainings table
    Schema::create('compliance_trainings', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->string('training_type');
      $table->date('due_date');
      $table->string('status');
      $table->text('content');
      $table->string('department');
      $table->boolean('is_mandatory')->default(true);
      $table->integer('duration_minutes');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('compliance_trainings');
    Schema::dropIfExists('compliance_notifications');
    Schema::dropIfExists('compliance_documents');
    Schema::dropIfExists('compliance_audits');
    Schema::dropIfExists('compliance_requirements');
  }
};
