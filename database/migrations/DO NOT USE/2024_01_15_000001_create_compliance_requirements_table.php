<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('compliance_requirements', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description');
      $table->string('regulation_type'); // GDPR, SOX, HIPAA, etc.
      $table->string('status')->default('active');
      $table->date('effective_date');
      $table->date('review_date');
      $table->text('requirements');
      $table->text('actions_needed')->nullable();
      $table->boolean('is_mandatory')->default(true);
      $table->string('risk_level')->default('medium');
      $table->string('department_scope')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('compliance_requirements');
  }
};
