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
    Schema::create('contacts', function (Blueprint $table) {
      $table->id();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->string('company')->nullable();
      $table->string('position')->nullable();
      $table->enum('type', ['customer', 'lead', 'prospect']);
      $table->string('status');
      $table->string('source')->nullable();
      $table->text('notes')->nullable();
      $table->string('contactable_type');
      $table->unsignedBigInteger('contactable_id');
      $table->timestamps();

      $table->index(['contactable_type', 'contactable_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('contacts');
  }
};
