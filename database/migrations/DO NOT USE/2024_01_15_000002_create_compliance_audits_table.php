<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('compliance_audits', function (Blueprint $table) {
      $table->id();
      $table->string('audit_type');
      $table->string('status');
      $table->date('scheduled_date');
      $table->date('completion_date')->nullable();
      $table->text('findings')->nullable();
      $table->text('recommendations')->nullable();
      $table->string('auditor_name');
      $table->string('department');
      $table->text('scope');
      $table->text('action_items')->nullable();
      $table->date('follow_up_date')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('compliance_audits');
  }
};
