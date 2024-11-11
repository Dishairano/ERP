<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('finance_budget_scenario_line_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('scenario_id')->constrained('finance_budget_scenarios')->cascadeOnDelete();
      $table->string('name');
      $table->string('category');
      $table->decimal('amount', 15, 2)->default(0);
      $table->text('description')->nullable();
      $table->timestamps();

      // Use a shorter name for the unique index
      $table->unique(['scenario_id', 'name', 'category'], 'scenario_line_items_unique');
    });
  }

  public function down()
  {
    Schema::dropIfExists('finance_budget_scenario_line_items');
  }
};
