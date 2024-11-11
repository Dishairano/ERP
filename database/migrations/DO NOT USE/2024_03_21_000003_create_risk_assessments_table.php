<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('risk_assessments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->foreignId('risk_id')->constrained('project_risks')->onDelete('cascade');
      $table->integer('probability'); // 1-5 scale
      $table->integer('impact'); // 1-5 scale
      $table->integer('risk_score'); // probability * impact
      $table->string('risk_level'); // Low, Medium, High, Critical
      $table->json('mitigation_strategies')->nullable();
      $table->json('contingency_plans')->nullable();
      $table->decimal('estimated_cost', 15, 2)->nullable();
      $table->date('assessment_date');
      $table->date('next_review_date')->nullable();
      $table->foreignId('assessed_by')->constrained('users');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('risk_assessment_updates', function (Blueprint $table) {
      $table->id();
      $table->foreignId('risk_assessment_id')->constrained()->onDelete('cascade');
      $table->string('status'); // Unchanged, Increased, Decreased, Mitigated
      $table->text('update_description');
      $table->json('changes')->nullable();
      $table->foreignId('updated_by')->constrained('users');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('risk_assessment_updates');
    Schema::dropIfExists('risk_assessments');
  }
};
