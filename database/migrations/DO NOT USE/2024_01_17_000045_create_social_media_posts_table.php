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
    Schema::create('social_media_posts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('platform_id')->constrained('social_media_platforms');
      $table->foreignId('campaign_id')->nullable()->constrained();
      $table->text('content');
      $table->json('media')->nullable();
      $table->timestamp('scheduled_at');
      $table->timestamp('published_at')->nullable();
      $table->json('target_audience')->nullable();
      $table->enum('status', ['draft', 'scheduled', 'published', 'failed'])->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->json('metrics')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('social_media_posts');
  }
};
