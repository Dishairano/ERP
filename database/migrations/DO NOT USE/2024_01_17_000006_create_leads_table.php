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
    Schema::create('leads', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('company_name');
      $table->string('industry')->nullable();
      $table->decimal('estimated_value', 12, 2)->nullable();
      $table->string('source')->nullable();
      $table->string('status');
      $table->foreignId('assigned_to')->nullable()->constrained('users');
      $table->date('expected_close_date')->nullable();
      $table->text('description')->nullable();
      $table->json('requirements')->nullable();
      $table->integer('probability')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('leads');
  }
};
