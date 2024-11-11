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
    Schema::create('access_control_lists', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('role');
      $table->string('resource_type')->nullable();
      $table->unsignedBigInteger('resource_id')->nullable();
      $table->string('permission_level');
      $table->timestamp('expires_at')->nullable();
      $table->foreignId('granted_by')->nullable()->constrained('users')->onDelete('set null');
      $table->timestamps();
      $table->softDeletes();

      $table->index(['resource_type', 'resource_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('access_control_lists');
  }
};
