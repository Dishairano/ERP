<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('overtimes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('date');
      $table->decimal('hours', 5, 2);
      $table->text('reason');
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('overtimes');
  }
};
