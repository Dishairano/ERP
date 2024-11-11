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
    Schema::create('email_campaigns', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('subject');
      $table->foreignId('template_id')->constrained('email_templates');
      $table->foreignId('campaign_id')->nullable()->constrained();
      $table->text('content');
      $table->timestamp('scheduled_at');
      $table->timestamp('sent_at')->nullable();
      $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed'])->default('draft');
      $table->foreignId('created_by')->constrained('users');
      $table->json('metrics')->nullable();
      $table->timestamps();
    });

    Schema::create('customer_segment_email_campaign', function (Blueprint $table) {
      $table->foreignId('customer_segment_id')->constrained();
      $table->foreignId('email_campaign_id')->constrained();
      $table->primary(['customer_segment_id', 'email_campaign_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_segment_email_campaign');
    Schema::dropIfExists('email_campaigns');
  }
};
