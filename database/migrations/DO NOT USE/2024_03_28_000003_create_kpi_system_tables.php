<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // KPI Definitions table
    Schema::create('kpi_definitions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique(); // Unique identifier for the KPI
      $table->text('description');
      $table->string('category'); // financial, hr, operational, sales, etc.
      $table->string('unit'); // percentage, currency, number, etc.
      $table->string('calculation_method'); // formula or method to calculate
      $table->string('data_source'); // table or external source
      $table->string('frequency'); // daily, weekly, monthly, etc.
      $table->json('visualization_settings')->nullable(); // chart type and settings
      $table->boolean('is_active')->default(true);
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Values table
    Schema::create('kpi_values', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kpi_definition_id')->constrained('kpi_definitions');
      $table->decimal('value', 15, 4);
      $table->timestamp('measurement_date');
      $table->string('dimension_type')->nullable(); // department, project, product, etc.
      $table->unsignedBigInteger('dimension_id')->nullable(); // ID reference to the dimension
      $table->json('additional_data')->nullable(); // Any additional context
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Thresholds table
    Schema::create('kpi_thresholds', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kpi_definition_id')->constrained('kpi_definitions');
      $table->decimal('warning_threshold', 15, 4);
      $table->decimal('critical_threshold', 15, 4);
      $table->string('comparison_operator'); // greater_than, less_than, etc.
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Notifications table
    Schema::create('kpi_notifications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kpi_definition_id')->constrained('kpi_definitions');
      $table->foreignId('kpi_threshold_id')->constrained('kpi_thresholds');
      $table->foreignId('kpi_value_id')->constrained('kpi_values');
      $table->string('severity'); // warning, critical
      $table->text('message');
      $table->json('recipients'); // Array of user IDs or roles
      $table->timestamp('read_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Targets table
    Schema::create('kpi_targets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kpi_definition_id')->constrained('kpi_definitions');
      $table->decimal('target_value', 15, 4);
      $table->date('start_date');
      $table->date('end_date');
      $table->string('dimension_type')->nullable(); // department, project, product, etc.
      $table->unsignedBigInteger('dimension_id')->nullable(); // ID reference to the dimension
      $table->text('description');
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Reports table
    Schema::create('kpi_reports', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description');
      $table->json('kpi_definitions'); // Array of KPI definition IDs
      $table->json('filters'); // Report filters
      $table->json('visualization_settings'); // Report layout and charts
      $table->string('frequency')->nullable(); // For automated reports
      $table->json('recipients')->nullable(); // For automated reports
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });

    // KPI Report Exports table
    Schema::create('kpi_report_exports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kpi_report_id')->constrained('kpi_reports');
      $table->string('format'); // pdf, excel, etc.
      $table->string('file_path');
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('kpi_report_exports');
    Schema::dropIfExists('kpi_reports');
    Schema::dropIfExists('kpi_targets');
    Schema::dropIfExists('kpi_notifications');
    Schema::dropIfExists('kpi_thresholds');
    Schema::dropIfExists('kpi_values');
    Schema::dropIfExists('kpi_definitions');
  }
};
