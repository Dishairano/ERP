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
    Schema::create('quality_measurements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('quality_inspection_id')->constrained()->onDelete('cascade');
      $table->string('parameter');
      $table->decimal('value', 10, 4);
      $table->string('unit');
      $table->decimal('specification_min', 10, 4)->nullable();
      $table->decimal('specification_max', 10, 4)->nullable();
      $table->enum('result', ['pass', 'fail'])->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('quality_measurements');
  }
};
