<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Create data_analysis_configs if it doesn't exist
    if (!Schema::hasTable('data_analysis_configs')) {
      Schema::create('data_analysis_configs', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type');
        $table->json('configuration');
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
        $table->softDeletes();
      });
    }

    // Create data_analysis_results if it doesn't exist
    if (!Schema::hasTable('data_analysis_results')) {
      Schema::create('data_analysis_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('config_id')->constrained('data_analysis_configs');
        $table->json('result_data');
        $table->timestamp('analyzed_at');
        $table->string('status')->default('completed');
        $table->text('error_message')->nullable();
        $table->timestamps();
      });
    }

    // Create data_visualizations if it doesn't exist
    if (!Schema::hasTable('data_visualizations')) {
      Schema::create('data_visualizations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('result_id')->constrained('data_analysis_results');
        $table->string('type');
        $table->json('configuration');
        $table->timestamps();
      });
    }

    // Create data_exports if it doesn't exist
    if (!Schema::hasTable('data_exports')) {
      Schema::create('data_exports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('result_id')->constrained('data_analysis_results');
        $table->string('format');
        $table->string('file_path');
        $table->timestamps();
      });
    }

    // Create data_forecasts if it doesn't exist
    if (!Schema::hasTable('data_forecasts')) {
      Schema::create('data_forecasts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('config_id')->constrained('data_analysis_configs');
        $table->json('forecast_data');
        $table->date('forecast_start');
        $table->date('forecast_end');
        $table->decimal('confidence_level', 5, 2);
        $table->timestamps();
      });
    }

    // Create data_dashboards if it doesn't exist
    if (!Schema::hasTable('data_dashboards')) {
      Schema::create('data_dashboards', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('user_id')->constrained();
        $table->json('layout');
        $table->boolean('is_public')->default(false);
        $table->timestamps();
      });
    }

    // Add visualization relationship to existing dashboard_components table if needed
    if (Schema::hasTable('dashboard_components') && !Schema::hasColumn('dashboard_components', 'data_visualization_id')) {
      Schema::table('dashboard_components', function (Blueprint $table) {
        $table->foreignId('data_visualization_id')->nullable()->after('dashboard_id')
          ->constrained('data_visualizations')->nullOnDelete();
      });
    }
  }

  public function down()
  {
    // Remove the added column from dashboard_components if it exists
    if (Schema::hasTable('dashboard_components') && Schema::hasColumn('dashboard_components', 'data_visualization_id')) {
      Schema::table('dashboard_components', function (Blueprint $table) {
        $table->dropConstrainedForeignId('data_visualization_id');
      });
    }

    // Drop tables in reverse order of creation
    Schema::dropIfExists('data_dashboards');
    Schema::dropIfExists('data_forecasts');
    Schema::dropIfExists('data_exports');
    Schema::dropIfExists('data_visualizations');
    Schema::dropIfExists('data_analysis_results');
    Schema::dropIfExists('data_analysis_configs');
  }
};
