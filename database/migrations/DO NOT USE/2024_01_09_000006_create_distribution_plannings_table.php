<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('distribution_plannings', function (Blueprint $table) {
      $table->id();
      $table->string('plan_name');
      $table->text('description')->nullable();
      $table->date('start_date');
      $table->date('end_date');
      $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('distribution_plannings');
  }
};
