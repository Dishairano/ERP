<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('dashboard_components', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->json('settings')->nullable();
      $table->integer('position')->default(0);
      $table->string('size')->default('medium'); // small, medium, large
      $table->integer('refresh_interval')->nullable(); // in seconds
      $table->boolean('is_enabled')->default(true);
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
      $table->foreignId('dashboard_id')->constrained()->onDelete('cascade');
      $table->string('data_source')->nullable();
      $table->string('visualization_type')->nullable();
      $table->json('custom_styles')->nullable();
      $table->json('permissions')->nullable();
      $table->timestamp('last_refresh')->nullable();
      $table->string('status')->default('active');
      $table->text('error_message')->nullable();
      $table->integer('row')->default(0);
      $table->integer('column')->default(0);
      $table->integer('width')->default(1);
      $table->integer('height')->default(1);
      $table->json('filters')->nullable();
      $table->json('aggregation_settings')->nullable();
      $table->boolean('is_public')->default(false);
      $table->string('cache_key')->nullable();
      $table->integer('cache_duration')->nullable(); // in seconds
      $table->timestamps();
      $table->softDeletes();

      // Indexes for better query performance
      $table->index('type');
      $table->index('is_enabled');
      $table->index('status');
      $table->index(['dashboard_id', 'position']);
    });

    // Create pivot table for component sharing
    Schema::create('dashboard_component_shares', function (Blueprint $table) {
      $table->id();
      $table->foreignId('dashboard_component_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('permission_level'); // view, edit, manage
      $table->timestamps();

      $table->unique(['dashboard_component_id', 'user_id']);
    });

    // Create table for component templates
    Schema::create('dashboard_component_templates', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->json('settings')->nullable();
      $table->string('category')->nullable();
      $table->text('description')->nullable();
      $table->json('default_config')->nullable();
      $table->boolean('is_system')->default(false);
      $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
      $table->timestamps();
      $table->softDeletes();

      $table->index('type');
      $table->index('category');
    });
  }

  public function down()
  {
    Schema::dropIfExists('dashboard_component_shares');
    Schema::dropIfExists('dashboard_component_templates');
    Schema::dropIfExists('dashboard_components');
  }
};
