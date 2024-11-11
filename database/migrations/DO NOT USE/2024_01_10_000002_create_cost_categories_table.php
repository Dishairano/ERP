<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('cost_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['operational', 'capital'])->default('operational');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('cost_categories');
  }
};
