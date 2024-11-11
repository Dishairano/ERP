<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetTransfersTable extends Migration
{
  public function up()
  {
    Schema::create('budget_transfers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('from_budget_id')->constrained('budgets');
      $table->foreignId('to_budget_id')->constrained('budgets');
      $table->decimal('amount', 15, 2);
      $table->text('reason');
      $table->foreignId('approved_by')->nullable()->constrained('users');
      $table->timestamp('approved_at')->nullable();
      $table->string('status')->default('pending');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('budget_transfers');
  }
}
