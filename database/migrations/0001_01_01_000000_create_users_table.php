<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email', 191)->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
    });

    // Insert a sample user
    DB::table('users')->insert([
      'name' => 'Dishairano de Boer',
      'email' => 'dishairano@jhosting.nl',
      'password' => Hash::make('Damian123123!'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Insert a sample user
    DB::table('users')->insert([
      'name' => 'Jagmeet Sachdeva',
      'email' => 'jagmeet44@live.nl',
      'password' => Hash::make('your_password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email', 191)->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
  }
};
