<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->string('action');
      $table->string('model_type')->nullable();
      $table->unsignedBigInteger('model_id')->nullable();
      $table->json('changes')->nullable();
      $table->string('ip_address', 45)->nullable();
      $table->string('user_agent')->nullable();
      $table->timestamps();

      $table->index(['model_type', 'model_id']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('audit_logs');
  }
};
