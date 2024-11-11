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
    if (!Schema::hasTable('leave_requests')) {
      Schema::create('leave_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->string('type');
        $table->date('start_date');
        $table->date('end_date');
        $table->string('status')->default('pending');
        $table->text('reason')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamp('approved_at')->nullable();
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
    Schema::dropIfExists('leave_requests');
  }
};
