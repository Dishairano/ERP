<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('project_risks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
      $table->string('title');
      $table->text('description');
      $table->string('category', 50);
      $table->integer('severity')->comment('1-5 scale');
      $table->integer('likelihood')->comment('1-5 scale');
      $table->text('impact');
      $table->text('mitigation_strategy');
      $table->enum('status', ['identified', 'assessed', 'mitigated', 'closed']);
      $table->date('due_date');
      $table->string('owner');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_risks');
  }
};
