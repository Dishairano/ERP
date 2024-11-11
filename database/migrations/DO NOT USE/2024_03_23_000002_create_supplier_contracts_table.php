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
    if (!Schema::hasTable('supplier_contracts')) {
      Schema::create('supplier_contracts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
        $table->string('contract_number');
        $table->string('title');
        $table->text('description')->nullable();
        $table->date('start_date');
        $table->date('end_date');
        $table->decimal('value', 15, 2)->nullable();
        $table->string('currency', 3)->default('EUR');
        $table->text('terms_conditions')->nullable();
        $table->text('payment_terms')->nullable();
        $table->integer('payment_days')->default(30);
        $table->boolean('auto_renewal')->default(false);
        $table->integer('renewal_notice_days')->default(30);
        $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
        $table->timestamps();
        $table->softDeletes();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('supplier_contracts');
  }
};
