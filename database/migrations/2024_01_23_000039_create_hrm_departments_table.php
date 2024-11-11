<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_departments', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('parent_department_id')->nullable()->constrained('hrm_departments')->nullOnDelete();
      $table->integer('level')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['name', 'code']);
      $table->index('is_active');
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_departments');
  }
};
