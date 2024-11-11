<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('dashboards', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->json('layout')->nullable();
      $table->json('widgets')->nullable();
      $table->boolean('is_default')->default(false);
      $table->boolean('is_public')->default(false);
      $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('dashboards');
  }
};
