<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('units', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->enum('type', ['length', 'weight', 'volume', 'time', 'quantity', 'other']);
      $table->foreignId('base_unit_id')->nullable()->constrained('units');
      $table->decimal('conversion_factor', 15, 4)->nullable();
      $table->boolean('status')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('units');
  }
};
