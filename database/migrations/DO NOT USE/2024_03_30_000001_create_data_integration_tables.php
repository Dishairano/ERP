<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    // Main integration configuration table
    Schema::create('data_integrations', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('source_type'); // CRM, accounting, external_db, etc.
      $table->string('connection_type'); // API, database, file_import, etc.
      $table->json('connection_details'); // Credentials, endpoints, etc.
      $table->boolean('is_active')->default(true);
      $table->integer('sync_interval')->nullable(); // In minutes
      $table->timestamp('last_sync')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    // Data mapping configurations
    Schema::create('data_mappings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('source_field');
      $table->string('target_field');
      $table->string('target_model');
      $table->string('data_type');
      $table->json('transformation_rules')->nullable();
      $table->timestamps();
    });

    // Sync history and logs
    Schema::create('sync_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('status'); // success, error, warning
      $table->text('message')->nullable();
      $table->integer('records_processed')->default(0);
      $table->integer('records_succeeded')->default(0);
      $table->integer('records_failed')->default(0);
      $table->json('error_details')->nullable();
      $table->timestamps();
    });

    // API configurations
    Schema::create('api_configurations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('api_key')->nullable();
      $table->string('api_secret')->nullable();
      $table->string('endpoint_url');
      $table->string('auth_type'); // basic, oauth, api_key
      $table->json('headers')->nullable();
      $table->json('additional_params')->nullable();
      $table->timestamps();
    });

    // Database connections
    Schema::create('database_connections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('driver'); // mysql, postgresql, etc.
      $table->string('host');
      $table->string('database');
      $table->string('username');
      $table->string('password');
      $table->integer('port')->nullable();
      $table->json('additional_config')->nullable();
      $table->timestamps();
    });

    // Integration schedules
    Schema::create('integration_schedules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('frequency'); // daily, hourly, custom
      $table->string('cron_expression')->nullable();
      $table->time('preferred_time')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });

    // Data validation rules
    Schema::create('data_validation_rules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('integration_id')->constrained('data_integrations')->onDelete('cascade');
      $table->string('field_name');
      $table->string('rule_type'); // required, format, range, custom
      $table->json('rule_config');
      $table->string('error_message');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('data_validation_rules');
    Schema::dropIfExists('integration_schedules');
    Schema::dropIfExists('database_connections');
    Schema::dropIfExists('api_configurations');
    Schema::dropIfExists('sync_logs');
    Schema::dropIfExists('data_mappings');
    Schema::dropIfExists('data_integrations');
  }
};
