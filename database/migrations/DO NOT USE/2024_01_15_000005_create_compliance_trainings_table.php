<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('compliance_trainings', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->string('training_type');
      $table->date('due_date');
      $table->string('status');
      $table->text('content');
      $table->string('department');
      $table->boolean('is_mandatory')->default(true);
      $table->integer('duration_minutes');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('compliance_trainings');
  }
};
