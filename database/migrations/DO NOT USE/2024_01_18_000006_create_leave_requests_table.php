<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('leave_requests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
      $table->date('start_date');
      $table->date('end_date');
      $table->text('reason');
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->text('comments')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('leave_requests');
  }
};
