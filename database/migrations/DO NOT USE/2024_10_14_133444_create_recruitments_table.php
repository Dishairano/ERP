<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('position');  // Job position
            $table->string('applicant_name');  // Name of the applicant
            $table->string('email');  // Applicant's email
            $table->string('phone');  // Applicant's phone number
            $table->text('resume')->nullable();  // Resume (path to the uploaded file)
            $table->enum('status', ['pending', 'reviewed', 'interviewed', 'hired', 'rejected'])->default('pending');  // Application status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}