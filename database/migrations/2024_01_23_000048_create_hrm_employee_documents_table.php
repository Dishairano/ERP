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
    Schema::create('hrm_employee_documents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained('hrm_employees')->cascadeOnDelete();
      $table->string('document_type'); // contract, id_proof, resume, certificate, visa, passport, etc.
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('file_path');
      $table->string('file_name');
      $table->string('file_type');
      $table->bigInteger('file_size'); // in bytes
      $table->date('issue_date')->nullable();
      $table->date('expiry_date')->nullable();
      $table->string('issuing_authority')->nullable();
      $table->string('document_number')->nullable();
      $table->string('verification_status')->default('pending'); // pending, verified, rejected
      $table->foreignId('verified_by')->nullable()->constrained('users');
      $table->timestamp('verified_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->boolean('is_confidential')->default(false);
      $table->string('access_level')->default('public'); // public, restricted, confidential
      $table->json('tags')->nullable();
      $table->json('metadata')->nullable();
      $table->string('version')->default('1.0');
      $table->string('status')->default('active'); // active, archived, expired
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('employee_id');
      $table->index('document_type');
      $table->index('document_number');
      $table->index('verification_status');
      $table->index('verified_by');
      $table->index('access_level');
      $table->index('status');
      $table->index('issue_date');
      $table->index('expiry_date');
      $table->index(['employee_id', 'document_type']);
      $table->index(['employee_id', 'status']);
      $table->index(['document_type', 'status']);
      $table->index(['verification_status', 'verified_at']);
      $table->index(['access_level', 'is_confidential']);
      $table->index(['issue_date', 'expiry_date']);
      $table->index(['employee_id', 'document_type', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hrm_employee_documents');
  }
};
