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
      $table->foreignId('client_id')->nullable()->constrained('customers')->nullOnDelete();
      $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
      $table->string('status')->default('planned');
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->decimal('budget', 15, 2)->default(0);
      $table->decimal('actual_cost', 15, 2)->default(0);
      $table->integer('progress_percentage')->default(0);
      $table->json('scope')->nullable();
      $table->boolean('is_template')->default(false);
      $table->foreignId('parent_project_id')->nullable()->constrained('projects')->nullOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('projects');
  }
};
