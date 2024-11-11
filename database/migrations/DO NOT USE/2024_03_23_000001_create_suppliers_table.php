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
    if (!Schema::hasTable('suppliers')) {
      Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('contact_person')->nullable();
        $table->string('email')->required();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->nullable();
        $table->string('postal_code')->nullable();
        $table->string('tax_number')->nullable();
        $table->string('registration_number')->nullable();
        $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
        $table->enum('classification', ['strategic', 'tactical', 'operational'])->nullable();
        $table->boolean('is_critical')->default(false);
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('suppliers');
  }
};
