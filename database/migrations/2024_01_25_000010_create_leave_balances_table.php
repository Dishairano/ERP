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
        // Drop existing table with foreign key checks disabled
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('leave_balances');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->integer('year');
            $table->decimal('total_days', 8, 2);
            $table->decimal('used_days', 8, 2)->default(0);
            $table->decimal('pending_days', 8, 2)->default(0);
            $table->decimal('remaining_days', 8, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add unique constraint to prevent duplicate balances
            $table->unique(['user_id', 'leave_type_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('leave_balances');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
