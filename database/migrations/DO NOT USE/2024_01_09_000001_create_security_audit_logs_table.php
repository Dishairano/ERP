<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('security_audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
      $table->string('event_type');
      $table->string('ip_address')->nullable();
      $table->string('location')->nullable();
      $table->string('user_agent')->nullable();
      $table->string('status')->default('success');
      $table->json('details')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('security_audit_logs');
  }
};
