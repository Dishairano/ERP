<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('availabilities', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('date');
      $table->time('start_time');
      $table->time('end_time');
      $table->enum('status', ['available', 'unavailable']);
      $table->text('reason')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('availabilities');
  }
};
