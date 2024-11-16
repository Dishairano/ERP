<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create new table with temporary name
        Schema::create('leave_types_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('days_per_year')->default(0);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('paid')->default(true);
            $table->boolean('allow_carry_forward')->default(false);
            $table->decimal('max_carry_forward_days', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Drop existing tables if they exist
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Rename new table to final name
        Schema::rename('leave_types_new', 'leave_types');

        // Create leave_requests table
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
