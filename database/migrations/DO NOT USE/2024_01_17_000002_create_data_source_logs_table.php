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
    Schema::create('data_source_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('data_source_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->string('message');
      $table->json('details')->nullable();
      $table->text('stack_trace')->nullable();
      $table->float('execution_time', 8, 2)->nullable();
      $table->integer('memory_usage')->nullable();
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
      $table->string('ip_address')->nullable();
      $table->json('request_data')->nullable();
      $table->json('response_data')->nullable();
      $table->string('status_code')->nullable();
      $table->string('severity')->default('info');
      $table->json('context')->nullable();
      $table->string('source')->nullable();
      $table->string('environment')->nullable();
      $table->json('performance_metrics')->nullable();
      $table->string('correlation_id')->nullable();
      $table->json('tags')->nullable();
      $table->timestamps();
    });

    Schema::create('data_source_log_aggregates', function (Blueprint $table) {
      $table->id();
      $table->foreignId('data_source_id')->constrained()->onDelete('cascade');
      $table->string('period_type');
      $table->timestamp('period_start');
      $table->timestamp('period_end')->nullable();
      $table->integer('total_logs');
      $table->integer('success_count')->default(0);
      $table->integer('error_count')->default(0);
      $table->integer('warning_count')->default(0);
      $table->float('average_execution_time')->nullable();
      $table->integer('average_memory_usage')->nullable();
      $table->json('error_distribution')->nullable();
      $table->json('performance_metrics')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_source_log_aggregates');
    Schema::dropIfExists('data_source_logs');
  }
};
