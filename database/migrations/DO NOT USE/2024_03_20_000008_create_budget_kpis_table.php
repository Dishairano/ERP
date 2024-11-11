<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetKpisTable extends Migration
{
  public function up()
  {
    Schema::create('budget_kpis', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description');
      $table->string('metric_type'); // percentage, amount, ratio
      $table->decimal('target_value', 15, 2);
      $table->decimal('actual_value', 15, 2)->default(0);
      $table->decimal('threshold_warning', 15, 2);
      $table->decimal('threshold_critical', 15, 2);
      $table->foreignId('budget_id')->constrained()->onDelete('cascade');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('budget_kpis');
  }
}
