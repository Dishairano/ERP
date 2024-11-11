<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('risks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->string('title');
      $table->text('description')->nullable();
      $table->enum('severity', ['low', 'medium', 'high', 'critical']);
      $table->integer('likelihood');
      $table->integer('impact');
      $table->text('mitigation_strategy')->nullable();
      $table->enum('status', ['identified', 'assessed', 'mitigated', 'closed']);
      $table->foreignId('identified_by')->constrained('users');
      $table->foreignId('assigned_to')->nullable()->constrained('users');
      $table->date('due_date')->nullable();
      $table->timestamp('identified_at')->useCurrent();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('risks');
  }
};
