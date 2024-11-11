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
    Schema::create('finance_customers', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->string('contact_person')->nullable();
      $table->string('email')->nullable();
      $table->string('phone')->nullable();
      $table->string('mobile')->nullable();
      $table->string('website')->nullable();
      $table->string('tax_number')->nullable();
      $table->string('registration_number')->nullable();
      $table->string('address_line1')->nullable();
      $table->string('address_line2')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('postal_code')->nullable();
      $table->string('country')->nullable();
      $table->string('currency', 3)->default('USD');
      $table->string('payment_terms')->nullable();
      $table->decimal('credit_limit', 15, 2)->default(0);
      $table->string('status')->default('active');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();

      // Indexes
      $table->index('code');
      $table->index('name');
      $table->index('status');
      $table->index(['city', 'country']);
      $table->index('currency');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('finance_customers');
  }
};
