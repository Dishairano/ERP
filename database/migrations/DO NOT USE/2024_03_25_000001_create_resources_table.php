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
    if (!Schema::hasTable('resources')) {
      Schema::create('resources', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type');
        $table->text('description')->nullable();
        $table->string('status');
        $table->json('capabilities')->nullable();
        $table->decimal('cost_per_hour', 10, 2)->nullable();
        $table->decimal('cost_per_day', 10, 2)->nullable();
        $table->integer('capacity')->default(1);
        $table->json('location_details')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('resources');
  }
};
