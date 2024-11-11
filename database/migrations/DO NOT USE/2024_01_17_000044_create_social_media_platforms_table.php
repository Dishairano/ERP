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
    Schema::create('social_media_platforms', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('api_key')->nullable();
      $table->string('api_secret')->nullable();
      $table->text('access_token')->nullable();
      $table->text('refresh_token')->nullable();
      $table->json('settings')->nullable();
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('social_media_platforms');
  }
};
