<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('dashboard_preferences', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->json('preferences')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('dashboard_preferences');
  }
};
