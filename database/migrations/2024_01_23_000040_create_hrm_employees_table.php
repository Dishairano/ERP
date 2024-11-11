<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('hrm_employees', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('department_id')->nullable()->constrained('hrm_departments')->nullOnDelete();
      $table->string('employee_id')->unique();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->date('date_of_birth')->nullable();
      $table->string('gender')->nullable();
      $table->text('address')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('country')->nullable();
      $table->string('postal_code')->nullable();
      $table->date('hire_date');
      $table->string('employment_status'); // full-time, part-time, contract, intern
      $table->string('job_title');
      $table->decimal('salary', 15, 2)->nullable();
      $table->json('benefits')->nullable();
      $table->string('emergency_contact_name')->nullable();
      $table->string('emergency_contact_phone')->nullable();
      $table->text('notes')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();

      // Add indexes
      $table->index(['first_name', 'last_name']);
      $table->index(['department_id', 'job_title']);
      $table->index('employment_status');
      $table->index('is_active');
    });
  }

  public function down()
  {
    Schema::dropIfExists('hrm_employees');
  }
};
