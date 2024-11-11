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
    Schema::create('bom_components', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bill_of_material_id')->constrained()->onDelete('cascade');
      $table->foreignId('component_id')->constrained('products');
      $table->decimal('quantity', 10, 4);
      $table->string('unit');
      $table->integer('position')->default(0);
      $table->text('notes')->nullable();
      $table->boolean('is_critical')->default(false);
      $table->integer('lead_time')->nullable()->comment('in days');
      $table->decimal('waste_percentage', 5, 2)->default(0);
      $table->timestamps();

      $table->unique(['bill_of_material_id', 'component_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bom_components');
  }
};
