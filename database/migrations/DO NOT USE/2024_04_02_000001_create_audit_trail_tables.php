<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Skip audit_logs table since it already exists

    // Audit settings table
    Schema::create('audit_settings', function (Blueprint $table) {
      $table->id();
      $table->string('key')->unique();
      $table->text('value');
      $table->string('description')->nullable();
      $table->timestamps();
    });

    // Audit notifications table
    Schema::create('audit_notifications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('title');
      $table->text('message');
      $table->string('type'); // info, warning, critical, etc.
      $table->timestamp('read_at')->nullable();
      $table->timestamps();
    });

    // Audit exports table
    Schema::create('audit_exports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('file_name');
      $table->string('file_path');
      $table->string('format'); // PDF, Excel, etc.
      $table->json('filters')->nullable(); // Export criteria
      $table->timestamp('downloaded_at')->nullable();
      $table->timestamps();
    });

    // Financial audit logs table
    Schema::create('financial_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('audit_log_id')->constrained('audit_logs')->onDelete('cascade');
      $table->string('transaction_type');
      $table->decimal('amount', 15, 2)->nullable();
      $table->string('currency', 3)->default('USD');
      $table->string('status');
      $table->string('reference_number')->nullable();
      $table->timestamps();
    });

    // HR audit logs table
    Schema::create('hr_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('audit_log_id')->constrained('audit_logs')->onDelete('cascade');
      $table->string('personnel_action');
      $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
      $table->string('department')->nullable();
      $table->text('details')->nullable();
      $table->timestamps();
    });

    // System audit logs table
    Schema::create('system_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('audit_log_id')->constrained('audit_logs')->onDelete('cascade');
      $table->string('component'); // Which system component was affected
      $table->string('action_type');
      $table->text('technical_details')->nullable();
      $table->boolean('requires_attention')->default(false);
      $table->timestamps();
    });

    // Document audit logs table
    Schema::create('document_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('audit_log_id')->constrained('audit_logs')->onDelete('cascade');
      $table->string('document_type');
      $table->string('document_name');
      $table->string('action'); // Created, modified, deleted, etc.
      $table->text('version_info')->nullable();
      $table->timestamps();
    });

    // Inventory audit logs table
    Schema::create('inventory_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('audit_log_id')->constrained('audit_logs')->onDelete('cascade');
      $table->string('item_code')->nullable();
      $table->string('movement_type'); // In, out, adjustment
      $table->integer('quantity')->nullable();
      $table->string('location')->nullable();
      $table->text('reason')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('inventory_audit_logs');
    Schema::dropIfExists('document_audit_logs');
    Schema::dropIfExists('system_audit_logs');
    Schema::dropIfExists('hr_audit_logs');
    Schema::dropIfExists('financial_audit_logs');
    Schema::dropIfExists('audit_exports');
    Schema::dropIfExists('audit_notifications');
    Schema::dropIfExists('audit_settings');
  }
};
