<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('user_security_settings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->boolean('two_factor_enabled')->default(false);
      $table->string('phone_number')->nullable();
      $table->integer('password_expiry_days')->default(90);
      $table->integer('max_login_attempts')->default(5);
      $table->integer('session_timeout_minutes')->default(30);
      $table->boolean('require_password_history')->default(true);
      $table->string('password_complexity_level')->default('high');
      $table->integer('failed_login_attempts')->default(0);
      $table->timestamp('last_password_change')->nullable();
      $table->timestamp('last_login_at')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('user_security_settings');
  }
};
