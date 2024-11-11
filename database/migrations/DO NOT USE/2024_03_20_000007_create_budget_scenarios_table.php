<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetScenariosTable extends Migration
{
  public function up()
  {
    Schema::create('budget_scenarios', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description');
      $table->foreignId('budget_id')->constrained()->onDelete('cascade');
      $table->decimal('modified_amount', 15, 2);
      $table->json('assumptions');
      $table->json('impact_analysis');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('budget_scenarios');
  }
}
