<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('shifts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->time('start_time');
      $table->time('end_time');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('shifts');
  }
};
