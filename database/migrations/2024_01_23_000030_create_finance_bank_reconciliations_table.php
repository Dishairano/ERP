<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('finance_bank_reconciliations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bank_account_id')->constrained('finance_bank_accounts')->cascadeOnDelete();
      $table->date('statement_date');
      $table->decimal('statement_balance', 15, 2);
      $table->decimal('book_balance', 15, 2);
      $table->decimal('difference', 15, 2);
      $table->string('status')->default('draft'); // draft, in_progress, completed
      $table->text('notes')->nullable();
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('completed_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('finance_bank_reconciliations');
  }
};
