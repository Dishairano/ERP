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
    Schema::create('resources', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type'); // e.g., 'human', 'equipment', 'material'
      $table->text('description')->nullable();
      $table->decimal('capacity', 10, 2)->nullable(); // Capacity in appropriate units
      $table->decimal('cost_rate', 10, 2)->nullable(); // Cost per unit time/usage
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('resources');
  }
};
