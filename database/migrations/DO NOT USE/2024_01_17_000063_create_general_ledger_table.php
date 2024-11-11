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
    Schema::create('general_ledger', function (Blueprint $table) {
      $table->id();
      $table->foreignId('account_id')->constrained();
      $table->foreignId('journal_id')->constrained();
      $table->date('date');
      $table->string('description');
      $table->decimal('debit', 12, 2)->default(0);
      $table->decimal('credit', 12, 2)->default(0);
      $table->string('reference');
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('general_ledger');
  }
};
