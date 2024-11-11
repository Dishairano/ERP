<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('leave_balances', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
      $table->year('year');
      $table->decimal('total_days', 5, 1);
      $table->decimal('used_days', 5, 1)->default(0);
      $table->decimal('pending_days', 5, 1)->default(0);
      $table->timestamps();

      $table->unique(['user_id', 'leave_type_id', 'year']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('leave_balances');
  }
};
