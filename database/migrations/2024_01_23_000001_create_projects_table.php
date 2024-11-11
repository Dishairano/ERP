<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('status')->default('planning');
      $table->string('priority')->default('medium');
      $table->dateTime('start_date')->nullable();
      $table->dateTime('end_date')->nullable();
      $table->decimal('budget', 15, 2)->nullable();
      $table->decimal('progress', 5, 2)->default(0);
      $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('template_id')->nullable()->constrained('project_templates')->nullOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('projects');
  }
};
