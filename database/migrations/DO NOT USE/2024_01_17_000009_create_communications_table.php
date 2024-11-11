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
    Schema::create('communications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('contact_id')->constrained();
      $table->string('type');
      $table->string('subject');
      $table->text('content');
      $table->enum('direction', ['inbound', 'outbound']);
      $table->string('status');
      $table->timestamp('sent_at')->nullable();
      $table->foreignId('sent_by')->nullable()->constrained('users');
      $table->timestamp('received_at')->nullable();
      $table->string('channel');
      $table->string('reference_type')->nullable();
      $table->unsignedBigInteger('reference_id')->nullable();
      $table->timestamps();

      $table->index(['reference_type', 'reference_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('communications');
  }
};
