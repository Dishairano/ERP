<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->decimal('evaluation_score', 5, 2);
            $table->date('evaluation_date');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};