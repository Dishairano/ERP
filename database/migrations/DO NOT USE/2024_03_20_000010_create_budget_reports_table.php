<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetReportsTable extends Migration
{
  public function up()
  {
    Schema::create('budget_reports', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type'); // department, project, cost_category, etc.
      $table->json('parameters');
      $table->json('data');
      $table->string('format')->default('pdf'); // pdf, excel, etc.
      $table->string('frequency')->nullable(); // daily, weekly, monthly
      $table->timestamp('last_generated_at')->nullable();
      $table->timestamp('next_generation_at')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('budget_reports');
  }
}
