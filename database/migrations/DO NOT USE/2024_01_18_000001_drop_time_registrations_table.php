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
    Schema::dropIfExists('time_registrations');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::create('time_registrations', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
    });
  }
};
