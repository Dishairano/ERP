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
    Schema::create('project_templates', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->json('default_phases')->nullable();
      $table->json('default_tasks')->nullable();
      $table->json('default_risks')->nullable();
      $table->json('default_team_structure')->nullable();
      $table->json('default_budget_allocation')->nullable();
      $table->boolean('is_active')->default(true);
      $table->foreignId('created_by')->constrained('users');
      $table->foreignId('updated_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_templates');
  }
};
